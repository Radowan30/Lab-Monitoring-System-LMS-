<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Fetch all rooms and their thresholds
        $sensors = Sensor::all();

        return view('settings', compact('sensors'));
    }

    public function save(Request $request)
    {
        foreach ($request->rooms as $sensor_id => $room) {
            Sensor::where('sensor_id', $sensor_id)->update([
                'temp_threshold' => $room['temperature'],
                'humidity_threshold' => $room['humidity'],
            ]);
        }

        return redirect()->route('settings.index')->with('status', 'Settings saved successfully!');
    }
}
