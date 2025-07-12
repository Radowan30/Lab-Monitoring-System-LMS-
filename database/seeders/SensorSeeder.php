<?php

namespace Database\Seeders;

use App\Models\Sensor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if no sensors exist
        if (Sensor::count() === 0) {
            try {
                // Try to use factory if Faker is available
                Sensor::factory()->create(['lab_room_name' => 'Preparation Lab', 'temp_threshold' => 25, 'humidity_threshold' => 60]);
                Sensor::factory()->create(['lab_room_name' => 'FETEM Room', 'temp_threshold' => 25, 'humidity_threshold' => 60]);
                Sensor::factory()->create(['lab_room_name' => 'FETEM Room Chiller', 'temp_threshold' => 20, 'humidity_threshold' => 60]);
                Sensor::factory()->create(['lab_room_name' => 'FESEM Room', 'temp_threshold' => 25, 'humidity_threshold' => 60]);
                Sensor::factory()->create(['lab_room_name' => 'FESEM Room Chiller', 'temp_threshold' => 20, 'humidity_threshold' => 60]);
            } catch (\Exception $e) {
                // Fallback: create sensors without factory if Faker is not available
                $this->createSensorsWithoutFactory();
            }
        }
    }

    /**
     * Create sensors without using factories (fallback when Faker is not available)
     */
    private function createSensorsWithoutFactory(): void
    {
        $sensors = [
            ['lab_room_name' => 'Preparation Lab', 'temp_threshold' => 25, 'humidity_threshold' => 60],
            ['lab_room_name' => 'FETEM Room', 'temp_threshold' => 25, 'humidity_threshold' => 60],
            ['lab_room_name' => 'FETEM Room Chiller', 'temp_threshold' => 20, 'humidity_threshold' => 60],
            ['lab_room_name' => 'FESEM Room', 'temp_threshold' => 25, 'humidity_threshold' => 60],
            ['lab_room_name' => 'FESEM Room Chiller', 'temp_threshold' => 20, 'humidity_threshold' => 60],
        ];

        foreach ($sensors as $sensorData) {
            Sensor::updateOrCreate(
                ['lab_room_name' => $sensorData['lab_room_name']],
                [
                    'temp_threshold' => $sensorData['temp_threshold'],
                    'humidity_threshold' => $sensorData['humidity_threshold'],
                ]
            );
        }
    }
}
