<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorsData;
use App\Models\Sensor;
use Carbon\Carbon;

class SensorsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if no sensor data exists
        if (SensorsData::count() === 0) {
            try {
                // Try to use factory if Faker is available
                SensorsData::factory()->count(50)->create();
            } catch (\Exception $e) {
                // Fallback: create sensor data without factory if Faker is not available
                $this->createSensorDataWithoutFactory();
            }
        }
    }

    /**
     * Create sensor data without using factories (fallback when Faker is not available)
     */
    private function createSensorDataWithoutFactory(): void
    {
        $sensors = Sensor::all();

        if ($sensors->isEmpty()) {
            return; // No sensors to create data for
        }

        // Create sample data for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            foreach ($sensors as $sensor) {
                // Create 3-5 readings per day per sensor
                $readingsPerDay = rand(3, 5);

                for ($j = 0; $j < $readingsPerDay; $j++) {
                    $time = $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59));

                    SensorsData::create([
                        'sensor_id' => $sensor->sensor_id,
                        'temperature' => rand(18, 30), // Random temperature between 18-30°C
                        'humidity' => rand(40, 80), // Random humidity between 40-80%
                        'recorded_at' => $time,
                    ]);
                }
            }
        }
    }
}
