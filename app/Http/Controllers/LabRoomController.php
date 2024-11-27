<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorsData;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LabRoomController extends Controller
{
    public function show_prep_lab()
    {
        return view('lab_rooms.prep-lab');
    }

    public function show_FETEM_room()
    {
        return view('lab_rooms.fetem-room');
    }

    public function show_FETEM_chiller()
    {
        return view('lab_rooms.fetem-chiller');
    }

    public function show_FESEM_room()
    {
        return view('lab_rooms.fesem-room');
    }

    public function show_FESEM_chiller()
    {
        return view('lab_rooms.fesem-chiller');
    }


    //getsensordata function

    public function getSensorData(Request $request)
    {
        $sensorId = $request->input('sensorId'); // Required parameter
        if (!$sensorId) {
            return response()->json(['error' => 'Sensor ID is required'], 400);
        }

        // Fetch the threshold values for the given sensor ID
        try {
            $sensor = Sensor::where('sensor_id', $sensorId)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Sensor not found'], 404);
        }

        // Retrieve threshold values
        $tempThreshold = $sensor->temp_threshold;
        $humidityThreshold = $sensor->humidity_threshold;

        $viewType = $request->input('viewType', 'days'); // Default to days
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        \Log::info('Sensor ID: ' . $sensorId);
        \Log::info('Start Date: ' . $startDate);
        \Log::info('End Date: ' . $endDate);

        // Default to last 7 days if no date range is provided
        if (!$startDate || !$endDate) {
            $endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays(7);
        } else {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        // Fetch data from the database for the given sensor ID
        $query = SensorsData::where('sensor_id', $sensorId)
            ->whereBetween('recorded_at', [$startDate, $endDate])
            ->orderBy('recorded_at', 'asc');

        // Group data based on viewType
        if ($viewType === 'weeks') {
            $query->selectRaw('CONCAT(DATE_FORMAT(DATE_SUB(recorded_at, INTERVAL WEEKDAY(recorded_at) DAY), "%b %e"), " - ", DATE_FORMAT(DATE_ADD(recorded_at, INTERVAL (6 - WEEKDAY(recorded_at)) DAY), "%b %e")) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
                ->groupBy('period');
        } elseif ($viewType === 'months') {
            $query->selectRaw('DATE_FORMAT(recorded_at, "%Y-%M") as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
                ->groupBy('period');
        } else {
            $query->selectRaw('DATE(recorded_at) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
                ->groupBy('period');
        }

        $data = $query->get();

        // Prepare data for the frontend
        $response = [
            'labels' => $data->pluck('period'),
            'temperature' => $data->pluck('avg_temp'),
            'humidity' => $data->pluck('avg_hum'),
            'limit_temp' => $data->pluck('avg_temp')->map(fn($temp) => $tempThreshold),
            'limit_hum' => $data->pluck('avg_hum')->map(fn($hum) => $humidityThreshold),
        ];

        return response()->json($response);
    }








    // //Get Sensor Data for Sensor ID 1 (Prep Lab)
    // public function getPrepLabSensorData(Request $request)
    // {
    //     $viewType = $request->input('viewType', 'days'); // Default to days
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');

    //     // Default to last 7 days if no date range is provided
    //     if (!$startDate || !$endDate) {
    //         $endDate = Carbon::now();
    //         $startDate = $endDate->copy()->subDays(7);
    //     } else {
    //         $startDate = Carbon::parse($startDate);
    //         $endDate = Carbon::parse($endDate);
    //     }

    //     // Fetch data from the database for sensor ID 1 (Prep Lab)
    //     $query = SensorsData::where('sensor_id', 1) // Filter for sensor_id = 1
    //         ->whereBetween('recorded_at', [$startDate, $endDate])
    //         ->orderBy('recorded_at', 'asc'); // Sort by recorded_at in ascending order

    //     // Group data based on viewType
    //     if ($viewType === 'weeks') {
    //         $query->selectRaw('CONCAT(DATE_FORMAT(DATE_SUB(recorded_at, INTERVAL WEEKDAY(recorded_at) DAY), "%b %e"), " - ", DATE_FORMAT(DATE_ADD(recorded_at, INTERVAL (6 - WEEKDAY(recorded_at)) DAY), "%b %e")) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } elseif ($viewType === 'months') {
    //         $query->selectRaw('DATE_FORMAT(recorded_at, "%Y-%M") as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } else {
    //         $query->selectRaw('DATE(recorded_at) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     }

    //     $data = $query->get();

    //     // Prepare data for the frontend
    //     $response = [
    //         'labels' => $data->pluck('period'),
    //         'temperature' => $data->pluck('avg_temp'),
    //         'humidity' => $data->pluck('avg_hum'),
    //         'limit_temp' => $data->pluck('avg_temp')->map(fn($temp) => 25),
    //         'limit_hum' => $data->pluck('avg_hum')->map(fn($hum) => 60),
    //     ];

    //     return response()->json($response);
    // }



    // //Get Sensor Data for Sensor ID 2 (FETEM Room)
    // public function getFETEMRoomSensorData(Request $request)
    // {
    //     $viewType = $request->input('viewType', 'days'); // Default to days
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');

    //     // Default to last 7 days if no date range is provided
    //     if (!$startDate || !$endDate) {
    //         $endDate = Carbon::now();
    //         $startDate = $endDate->copy()->subDays(7);
    //     } else {
    //         $startDate = Carbon::parse($startDate);
    //         $endDate = Carbon::parse($endDate);
    //     }

    //     // Fetch data from the database for sensor ID 2 (FETEM Room)
    //     $query = SensorsData::where('sensor_id', 2) // Filter for sensor_id = 2
    //         ->whereBetween('recorded_at', [$startDate, $endDate])
    //         ->orderBy('recorded_at', 'asc'); // Sort by recorded_at in ascending order

    //     // Group data based on viewType
    //     if ($viewType === 'weeks') {
    //         $query->selectRaw('CONCAT(DATE_FORMAT(DATE_SUB(recorded_at, INTERVAL WEEKDAY(recorded_at) DAY), "%b %e"), " - ", DATE_FORMAT(DATE_ADD(recorded_at, INTERVAL (6 - WEEKDAY(recorded_at)) DAY), "%b %e")) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } elseif ($viewType === 'months') {
    //         $query->selectRaw('DATE_FORMAT(recorded_at, "%Y-%M") as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } else {
    //         $query->selectRaw('DATE(recorded_at) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     }

    //     $data = $query->get();

    //     // Prepare data for the frontend
    //     $response = [
    //         'labels' => $data->pluck('period'),
    //         'temperature' => $data->pluck('avg_temp'),
    //         'humidity' => $data->pluck('avg_hum'),
    //         'limit_temp' => $data->pluck('avg_temp')->map(fn($temp) => 25),
    //         'limit_hum' => $data->pluck('avg_hum')->map(fn($hum) => 60),
    //     ];

    //     return response()->json($response);
    // }



    // //Get Sensor Data for Sensor ID 3 (FETEM Chiller)
    // public function getFETEMChillerSensorData(Request $request)
    // {
    //     $viewType = $request->input('viewType', 'days'); // Default to days
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');

    //     // Default to last 7 days if no date range is provided
    //     if (!$startDate || !$endDate) {
    //         $endDate = Carbon::now();
    //         $startDate = $endDate->copy()->subDays(7);
    //     } else {
    //         $startDate = Carbon::parse($startDate);
    //         $endDate = Carbon::parse($endDate);
    //     }

    //     // Fetch data from the database for sensor ID 3 (FETEM Chiller)
    //     $query = SensorsData::where('sensor_id', 3) // Filter for sensor_id = 3
    //         ->whereBetween('recorded_at', [$startDate, $endDate])
    //         ->orderBy('recorded_at', 'asc'); // Sort by recorded_at in ascending order

    //     // Group data based on viewType
    //     if ($viewType === 'weeks') {
    //         $query->selectRaw('CONCAT(DATE_FORMAT(DATE_SUB(recorded_at, INTERVAL WEEKDAY(recorded_at) DAY), "%b %e"), " - ", DATE_FORMAT(DATE_ADD(recorded_at, INTERVAL (6 - WEEKDAY(recorded_at)) DAY), "%b %e")) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } elseif ($viewType === 'months') {
    //         $query->selectRaw('DATE_FORMAT(recorded_at, "%Y-%M") as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } else {
    //         $query->selectRaw('DATE(recorded_at) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     }

    //     $data = $query->get();

    //     // Prepare data for the frontend
    //     $response = [
    //         'labels' => $data->pluck('period'),
    //         'temperature' => $data->pluck('avg_temp'),
    //         'humidity' => $data->pluck('avg_hum'),
    //         'limit_temp' => $data->pluck('avg_temp')->map(fn($temp) => 25),
    //         'limit_hum' => $data->pluck('avg_hum')->map(fn($hum) => 60),
    //     ];

    //     return response()->json($response);
    // }



    // //Get Sensor Data for Sensor ID 4 (FESEM Room)
    // public function getFESEMRoomSensorData(Request $request)
    // {
    //     $viewType = $request->input('viewType', 'days'); // Default to days
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');

    //     // Default to last 7 days if no date range is provided
    //     if (!$startDate || !$endDate) {
    //         $endDate = Carbon::now();
    //         $startDate = $endDate->copy()->subDays(7);
    //     } else {
    //         $startDate = Carbon::parse($startDate);
    //         $endDate = Carbon::parse($endDate);
    //     }

    //     // Fetch data from the database for sensor ID 4 (FESEM Room)
    //     $query = SensorsData::where('sensor_id', 4) // Filter for sensor_id = 4
    //         ->whereBetween('recorded_at', [$startDate, $endDate])
    //         ->orderBy('recorded_at', 'asc'); // Sort by recorded_at in ascending order

    //     // Group data based on viewType
    //     if ($viewType === 'weeks') {
    //         $query->selectRaw('CONCAT(DATE_FORMAT(DATE_SUB(recorded_at, INTERVAL WEEKDAY(recorded_at) DAY), "%b %e"), " - ", DATE_FORMAT(DATE_ADD(recorded_at, INTERVAL (6 - WEEKDAY(recorded_at)) DAY), "%b %e")) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } elseif ($viewType === 'months') {
    //         $query->selectRaw('DATE_FORMAT(recorded_at, "%Y-%M") as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } else {
    //         $query->selectRaw('DATE(recorded_at) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     }

    //     $data = $query->get();

    //     // Prepare data for the frontend
    //     $response = [
    //         'labels' => $data->pluck('period'),
    //         'temperature' => $data->pluck('avg_temp'),
    //         'humidity' => $data->pluck('avg_hum'),
    //         'limit_temp' => $data->pluck('avg_temp')->map(fn($temp) => 25),
    //         'limit_hum' => $data->pluck('avg_hum')->map(fn($hum) => 60),
    //     ];

    //     return response()->json($response);
    // }



    // //Get Sensor Data for Sensor ID 5 (FESEM Chiller)
    // public function getFESEMChillerSensorData(Request $request)
    // {
    //     $viewType = $request->input('viewType', 'days'); // Default to days
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');

    //     // Default to last 7 days if no date range is provided
    //     if (!$startDate || !$endDate) {
    //         $endDate = Carbon::now();
    //         $startDate = $endDate->copy()->subDays(7);
    //     } else {
    //         $startDate = Carbon::parse($startDate);
    //         $endDate = Carbon::parse($endDate);
    //     }

    //     // Fetch data from the database for sensor ID 5 (FESEM Room)
    //     $query = SensorsData::where('sensor_id', 5) // Filter for sensor_id = 5
    //         ->whereBetween('recorded_at', [$startDate, $endDate])
    //         ->orderBy('recorded_at', 'asc'); // Sort by recorded_at in ascending order

    //     // Group data based on viewType
    //     if ($viewType === 'weeks') {
    //         $query->selectRaw('CONCAT(DATE_FORMAT(DATE_SUB(recorded_at, INTERVAL WEEKDAY(recorded_at) DAY), "%b %e"), " - ", DATE_FORMAT(DATE_ADD(recorded_at, INTERVAL (6 - WEEKDAY(recorded_at)) DAY), "%b %e")) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } elseif ($viewType === 'months') {
    //         $query->selectRaw('DATE_FORMAT(recorded_at, "%Y-%M") as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     } else {
    //         $query->selectRaw('DATE(recorded_at) as period, AVG(temperature) as avg_temp, AVG(humidity) as avg_hum')
    //             ->groupBy('period');
    //     }

    //     $data = $query->get();

    //     // Prepare data for the frontend
    //     $response = [
    //         'labels' => $data->pluck('period'),
    //         'temperature' => $data->pluck('avg_temp'),
    //         'humidity' => $data->pluck('avg_hum'),
    //         'limit_temp' => $data->pluck('avg_temp')->map(fn($temp) => 25),
    //         'limit_hum' => $data->pluck('avg_hum')->map(fn($hum) => 60),
    //     ];

    //     return response()->json($response);
    // }
}
