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
        // Fetch the selected lab room name and date range
        $labRoomName = $request->input('lab_room_name');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch data from the sensors_data table based on the lab room name and date range
        $data = DB::table('sensors_data')
            ->join('sensors', 'sensors.sensor_id', '=', 'sensors_data.sensor_id')
            ->where('sensors.lab_room_name', $labRoomName)
            ->whereBetween('sensors_data.recorded_at', [$startDate, $endDate])
            ->select('sensors_data.temperature', 'sensors_data.humidity', 'sensors_data.recorded_at as datetime')
            ->get();

        // Prepare the CSV header and rows
        $csvData = [];
        $csvData[] = ["Lab Room: $labRoomName"]; // Add the lab room name as a heading
        $csvData[] = ['Temperature', 'Humidity', 'DateTime']; // Add column headers

        foreach ($data as $row) {
            $csvData[] = [$row->temperature, $row->humidity, $row->datetime];
        }

        // Generate the CSV content
        $filename = 'report.csv'; // Fixed filename
        $csvOutput = fopen('php://output', 'w');
        ob_start();

        foreach ($csvData as $csvRow) {
            fputcsv($csvOutput, $csvRow);
        }

        fclose($csvOutput);
        $content = ob_get_clean();

        // Return the CSV as a downloadable response
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            // Add CSV headers
            fputcsv($handle, ['Temperature', 'Humidity', 'Datetime']);
            // Write rows to the CSV
            foreach ($data as $row) {
                fputcsv($handle, [(string) $row->temperature, (string) $row->humidity, $row->datetime]);
            }
            fclose($handle);
        }, 'sensors_data.csv', ['Content-Type' => 'text/csv']);
    }
    public function generateReport(Request $request)
    {
        $labRoomName = $request->input('labRoom');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $problemDescription = $request->input('problemDescription', 'No description provided.');

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
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_temp' => $data->max_temp ? round($data->max_temp, 2) : 'N/A',
            'max_hum' => $data->max_hum ? round($data->max_hum, 2) : 'N/A',
            'avg_temp' => $data->avg_temp ? round($data->avg_temp, 2) : 'N/A',
            'avg_hum' => $data->avg_hum ? round($data->avg_hum, 2) : 'N/A',
            'problem_desc' => $problemDescription,
        ];

        // Return data for preview via AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $summary,
            ]);
        }

        // Otherwise, return the full report view
        return view('report_pdf', compact('summary'));
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



}