<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\SensorsData;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;




class ReportController extends Controller
{
    public function showReport()
    {
        return view('report');
    }

    public function getSensorData(Request $request)
    {

        $request->validate([
            'sensorId' => 'required|integer',
            'viewType' => 'nullable|string|in:days,weeks,months',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
        ]);

        $sensorId = $request->input('sensorId');
        $viewType = $request->input('viewType', 'days');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Fetch the threshold values for the given sensor ID
        try {
            $sensor = Sensor::where('sensor_id', $sensorId)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Sensor not found'], 404);
        }

        // Retrieve threshold values
        $tempThreshold = $sensor->temp_threshold;
        $humidityThreshold = $sensor->humidity_threshold;

        // Query the database
        $query = DB::table('sensors_data')
            ->where('sensor_id', $sensorId);

        if ($startDate && $endDate) {
            $query->whereBetween('recorded_at', [$startDate, $endDate]);
        }

        if ($viewType === 'weeks') {
            $query->selectRaw('YEARWEEK(recorded_at) as date, 
                          AVG(temperature) as avg_temp, 
                          AVG(humidity) as avg_hum')
                ->groupBy('date');
        } elseif ($viewType === 'months') {
            $query->selectRaw('DATE_FORMAT(recorded_at, "%Y-%m") as date, 
                          AVG(temperature) as avg_temp, 
                          AVG(humidity) as avg_hum')
                ->groupBy('date');
        } else {
            $query->selectRaw('DATE(recorded_at) as date, 
                          AVG(temperature) as avg_temp, 
                          AVG(humidity) as avg_hum')
                ->groupBy('date');
        }

        $data = $query->orderBy('date')->get();

        // Prepare response
        $labels = [];
        $temperature = [];
        $humidity = [];
        $limit_temp = [];
        $limit_hum = [];

        foreach ($data as $row) {
            $labels[] = $row->date;
            $temperature[] = (float) $row->avg_temp;
            $humidity[] = (float) $row->avg_hum;

            // Append threshold values for visualization
            $limit_temp[] = $tempThreshold;
            $limit_hum[] = $humidityThreshold;
        }

        return response()->json([
            'labels' => $labels,
            'temperature' => $temperature,
            'humidity' => $humidity,
            'limit_temp' => $limit_temp,
            'limit_hum' => $limit_hum,
        ]);
    }
    public function downloadCsv(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'lab_room_name' => 'required|string', // Required lab room name
            'start_date' => 'required|date',      // Start date must be a valid date
            'end_date' => 'required|date|after_or_equal:start_date', // End date must be on/after the start date
        ]);

        // Retrieve request parameters
        $labRoomName = $request->input('lab_room_name'); // The name of the lab room
        $startDate = $request->input('start_date');      // The start date for the filter
        $endDate = $request->input('end_date');          // The end date for the filter

        // Fetch data from the database using a join
        $data = DB::table('sensors_data')
            ->join('sensors', 'sensors.sensor_id', '=', 'sensors_data.sensor_id')
            ->where('sensors.lab_room_name', $labRoomName) // Filter by lab room name
            ->whereBetween('sensors_data.recorded_at', [$startDate, $endDate]) // Filter by date range
            ->select(
                'sensors_data.temperature', // Select temperature from sensors_data
                'sensors_data.humidity',    // Select humidity from sensors_data
                'sensors_data.recorded_at as datetime' // Select recorded_at as datetime
            )
            ->get();

        // Check if any data is returned
        if ($data->isEmpty()) {
            return response()->json(['error' => 'No data found for the selected parameters.'], 404);
        }

        // Generate CSV data
        $csvData = [];
        $csvData[] = ["Lab Room: $labRoomName"];
        $csvData[] = ['Temperature', 'Humidity', 'Datetime']; // Add CSV headers
        foreach ($data as $row) {
            $csvData[] = [$row->temperature, $row->humidity, $row->datetime]; // Add each row of data
        }

        // Return the CSV file as a downloadable response
        return response()->streamDownload(function () use ($csvData) {
            $output = fopen('php://output', 'w'); // Open PHP output stream
            foreach ($csvData as $row) {
                fputcsv($output, $row); // Write each row to the CSV file
            }
            fclose($output); // Close the output stream
        }, 'sensors_data.csv', ['Content-Type' => 'text/csv']); // Return as CSV
    }


    public function generateReport(Request $request)
    {
        $labRoomName = $request->input('lab_room_name');
        $startDate = Carbon::parse($request->input('start_date'))->toDateString();
        $endDate = Carbon::parse($request->input('end_date'))->toDateString();
        $problemDescription = $request->input('problemDescription');
        $chartImage = $request->input('chartImage');

        // Clean up the base64 image data
        if ($chartImage) {
            // Remove data URL prefix if present
            $chartImage = str_replace('data:image/png;base64,', '', $chartImage);
            $chartImage = str_replace(' ', '+', $chartImage);
        }

        if (empty($problemDescription)) {
            $problemDescription = 'No issues reported.';
        }

        // Query the database to fetch data
        $data = DB::table('sensors_data')
            ->join('sensors', 'sensors.sensor_id', '=', 'sensors_data.sensor_id')
            ->where('sensors.lab_room_name', $labRoomName)
            ->whereBetween('sensors_data.recorded_at', [$startDate, $endDate])
            ->select(
                DB::raw('MAX(sensors_data.temperature) as max_temp'),
                DB::raw('MAX(sensors_data.humidity) as max_hum'),
                DB::raw('AVG(sensors_data.temperature) as avg_temp'),
                DB::raw('AVG(sensors_data.humidity) as avg_hum')
            )
            ->first();

        // Prepare the summary data
        $summary = [
            'lab_room_name' => $labRoomName,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_temp' => $data->max_temp !== null ? round($data->max_temp, 2) : 0,
            'max_hum' => $data->max_hum !== null ? round($data->max_hum, 2) : 0,
            'avg_temp' => $data->avg_temp !== null ? round($data->avg_temp, 2) : 0,
            'avg_hum' => $data->avg_hum !== null ? round($data->avg_hum, 2) : 0,
            'problem_desc' => $problemDescription,
            'chart_image' => $chartImage
        ];

        // Check if the request is for preview or PDF
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $summary,
            ]);
        } else {
            // Generate PDF
            $pdf = Pdf::loadView('report_pdf', compact('summary'));
            return $pdf->download('lab_report.pdf');
        }
    }

    public function getSummaryData(Request $request)
    {
        // Fetch problem description from the request
        $problemDescription = $request->input('problemDescription', 'No description provided.');

        // Fetch graph data from the request
        $graphData = $request->input('graphData', []);

        return [
            'graph' => $graphData,
            'problemDescription' => $problemDescription,
        ];
    }

    public function getSensorId(Request $request)
    {
        $labRoomName = $request->input('lab_room_name');

        // Validate lab room name
        if (!$labRoomName) {
            return response()->json(['error' => 'Lab room name is required.'], 400);
        }

        // Find the sensor ID
        $sensor = Sensor::where('lab_room_name', $labRoomName)->first();

        if (!$sensor) {
            return response()->json(['error' => 'Sensor not found for the selected lab room.'], 404);
        }

        return response()->json(['sensor_id' => $sensor->sensor_id]);
    }




}