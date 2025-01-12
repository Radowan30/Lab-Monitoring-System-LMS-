<?php

namespace App\Http\Controllers;

use App\Mail\ThresholdAlert;
use App\Models\Notification;
use App\Models\Sensor;
use App\Models\SensorsData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TelegramNotification;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;
use Illuminate\Support\Facades\Notification as NotificationFacade;


class LabRoomController extends Controller
{
    public function show_prep_lab()
    {
        return view('lab_rooms.prep-lab');
    }

    public function show_FETEM_room()
    {
        return view('lab_rooms.fetem$sensor');
    }

    public function show_FETEM_chiller()
    {
        return view('lab_rooms.fetem-chiller');
    }

    public function show_FESEM_room()
    {
        return view('lab_rooms.fesem$sensor');
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



    public function putSensor1Info(Request $request)
    {
        $temperature = $request->input('temperature');
        $humidity = $request->input('humidity');
        $sensorID = 1;


        // ****Store in database only at specific times****

        $storeHours = [3, 9, 15, 21];  // Define the hours you want to check

        $currentHour = Carbon::now()->hour; // Get the current hour
        $currentMinute = Carbon::now()->minute; // Get the current minute

        // Check if the current time is in the storeHours array and it's within the first 2 minutes of the hour
        if (in_array($currentHour, $storeHours) && $currentMinute <= 1) {
            $sensorExists = Sensor::where('sensor_id', 1)->exists();

            if (!$sensorExists) {
                return response()->json(['error' => 'Invalid sensor_id'], 400);
            }

            SensorsData::create([
                'sensor_id' => 1,
                'temperature' => $temperature,
                'humidity' => $humidity,
                'recorded_at' => now(),
            ]);

            Log::info("Data stored: temperature={$temperature}, humidity={$humidity}");
        }

        Cache::put("sensor_{$sensorID}_temperature", $temperature, 60);
        Cache::put("sensor_{$sensorID}_humidity", $humidity, 60);



        // ****Threshold violation check****

        // Retrieve threshold values from the database
        try {
            $sensor = Sensor::where('sensor_id', $sensorID)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Sensor not found'], 404);
        }

        $tempThreshold = $sensor->temp_threshold;
        $humidityThreshold = $sensor->humidity_threshold;

        // Debugging information
        Log::info("Sensor ID: $sensorID, Temperature: $temperature, Humidity: $humidity");
        Log::info("Thresholds - Temperature: $tempThreshold, Humidity: $humidityThreshold");

        // Cache keys
        $violationKey = "sensor_{$sensorID}_violation_count";
        $totalKey = "sensor_{$sensorID}_total_count";
        $timeKey = "sensor_{$sensorID}_first_violation_time";

        // Retrieve counters and timestamp from cache
        $violationCount = Cache::get($violationKey, 0);
        $totalCount = Cache::get($totalKey, 0);
        $firstViolationTime = Cache::get($timeKey, null);


        Log::info(" Violation count: $violationCount");

        // Fetch thresholds from the database
        // $sensor = Sensor::find($sensorID);
        // $tempThreshold = $sensor->temp_threshold;
        // $humidThreshold = $sensor->humidity_threshold;


        // If a threshold violation occurs, update the counter and the first violation time
        if ($temperature > $tempThreshold || $humidity > $humidityThreshold) {
            // Increment the violation count
            $violationCount++;

            Log::info(" Violation count inside if condition: $violationCount");

            // If it's the first violation, record the timestamp
            if ($firstViolationTime === null) {
                $firstViolationTime = Carbon::now();
                Cache::put($timeKey, $firstViolationTime, now()->addMinutes(30));
            }
        }


        // Increment the total count of readings
        $totalCount++;

        // Update counters in the cache
        Cache::put($violationKey, $violationCount, now()->addMinutes(30));
        Cache::put($totalKey, $totalCount, now()->addMinutes(30));

        Log::info("Total count: $totalCount, Violation count: $violationCount, Violation time: $firstViolationTime, Current time: " . Carbon::now()->toDateTimeString());

        // Check if 30 minutes have passed since the first violation
        if ($firstViolationTime !== null && $firstViolationTime->diffInMinutes(Carbon::now()) >= 1) {

            Log::info("One minute from the first violation. Violation count: $violationCount, Total count: $totalCount");

            // Check if the majority of readings in the last 30 minutes violated the threshold
            if ($totalCount > 0 && $violationCount > ($totalCount / 2)) {

                $currentTime = Carbon::now();

                // Save notification to the database
                Notification::create([
                    'message' => "Temperature and/or humidity above threshold in Room $sensor->lab_room_name. Recorded temperature: $temperature °C, humidity: $humidity%. Thresholds: temperature $tempThreshold °C, humidity $humidityThreshold%.",
                    'sensor_id' => $sensorID,
                ]);


                //*Email Sending*

                // Queue email notifications
                $this->sendEmailNotifications($sensor, $temperature, $humidity, $tempThreshold, $humidityThreshold, $currentTime);

                Log::info("Notification sent to emails.");


                //*Telegram Sending*
                NotificationFacade::route('telegram', '-4637386839')->notify(new TelegramNotification($sensor, $temperature, $humidity, $tempThreshold, $humidityThreshold, $currentTime));


            }
            // Reset counters and timestamp after the 30-minute period ends
            Cache::forget($violationKey);
            Cache::forget($totalKey);
            Cache::forget($timeKey);
        }
    }


    public function getSensor1Info(Request $request)
    {
        $temperature = Cache::get('sensor_1_temperature', null);
        $humidity = Cache::get('sensor_1_humidity', null);

        if (is_null($temperature) || is_null($humidity)) {
            return response()->json(['error' => 'No valid data in cache'], 400);
        }

        return response()->json([
            'temperature' => $temperature,
            'humidity' => $humidity,
        ]);
    }


    //Queue Email Sending method
    public function sendEmailNotifications($sensor, $temperature, $humidity, $tempThreshold, $humidThreshold, $currentTime)
    {
        $users = User::all(); // Assuming all users should be notified
        foreach ($users as $user) {
            Mail::to($user->email)->queue(new ThresholdAlert($sensor, $temperature, $humidity, $tempThreshold, $humidThreshold, $currentTime));
        }
    }

    // }

    public function showSensorInfo(Request $request)
    {

        DB::enableQueryLog();

        // if ($request->isMethod('post')) {
        //     // Handle POST request from ESP32
        //     $temperature = (float)$request->input('temperature');
        //     $humidity = (float)$request->input('humidity');

        //     // Check if sensor exists
        //     $sensorExists = Sensor::where('sensor_id', 1)->exists();

        //     // Log::info('Incoming Data from ESP32: ', $request->all());

        //     // Store data in Laravel's cache
        //     Cache::put('temperature', $temperature, 60); // Cache for 60 seconds
        //     Cache::put('humidity', $humidity, 60);

        //     // $validatedData = $request->validate([
        //     //     'temperature' => 'required|numeric',
        //     //     'humidity' => 'required|numeric',
        //     // ]);

        //     // Store in database only at specific times
        //     // $storeHours = [3, 9, 15, 21];  // Define the hours you want to check

        //     // $currentHour = Carbon::now()->hour; // Get the current hour
        //     // $currentMinute = Carbon::now()->minute; // Get the current minute
        //     // $currentSecond = Carbon::now()->second; // Get the current second

        //     // Check if the current time is in the storeHours array and it's within the first 5 seconds of the hour
        //     // if (in_array($currentHour, $storeHours) && $currentMinute === 0 && $currentSecond <= 5) { //may need to do $currentMinute>=0 and $currentMinute<=1
        //         // Store the data in the database
        //         $sensor = Sensor::where('sensor_id', 1)->first();
        //         if ($sensor) {
        //             try {
        //                 SensorsData::create([
        //                     'sensor_id' => 1,
        //                     'temperature' => 'temperature',
        //                     'humidity' => 'humidity',
        //                     'recorded_at' => now(),
        //                 ]);
        //             } catch (\Exception $e) {
        //                 Log::error("Failed to insert sensor data: " . $e->getMessage());
        //                 return response()->json(['error' => 'Failed to save data'], 500);
        //             }
        //         } else {
        //             Log::error("Sensor with ID 1 not found. Unable to save data.");
        //         }
        //     // }

        //     return response()->json(['message' => 'Data received']);
        if ($request->isMethod('post')) {
            $temperature = $request->input('temperature');
            $humidity = $request->input('humidity');


            // try {

            // Cache operations...
            Cache::put('temperature', $temperature, 60);
            Cache::put('humidity', $humidity, 60);

            //     return response()->json(['message' => 'Data received and stored']);
            // } catch (\Exception $e) {
            //     Log::error('Error storing sensor data: ' . $e->getMessage());
            //     return response()->json(['error' => 'Failed to store data'], 500);
            // }
        } elseif ($request->isMethod('get')) {
            $temperature = Cache::get('temperature', null);
            $humidity = Cache::get('humidity', null);

            if (is_null($temperature) || is_null($humidity)) {
                return response()->json(['error' => 'No valid data in cache'], 400);
            }

            $sensorExists = Sensor::where('sensor_id', 1)->exists();

            if (!$sensorExists) {
                return response()->json(['error' => 'Invalid sensor_id'], 400);
            }

            try {
                SensorsData::create([
                    'sensor_id' => 1,
                    'temperature' => $temperature,
                    'humidity' => $humidity,
                    'recorded_at' => now(),
                ]);

                Log::info("Data stored: temperature={$temperature}, humidity={$humidity}");

                return response()->json([
                    'temperature' => $temperature,
                    'humidity' => $humidity,
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to store data: " . $e->getMessage());
                return response()->json(['error' => 'Failed to store data'], 500);
            }
        }
    }

}
