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
        Sensor::factory()->create(['lab_room_name' => 'Preparation Lab', 'temp_threshold' => 25, 'humidity_threshold' => 60]);
        Sensor::factory()->create(['lab_room_name' => 'FETEM Room', 'temp_threshold' => 25, 'humidity_threshold' => 60]);
        Sensor::factory()->create(['lab_room_name' => 'FETEM Room Chiller', 'temp_threshold' => 20, 'humidity_threshold' => 60]);
        Sensor::factory()->create(['lab_room_name' => 'FESEM Room', 'temp_threshold' => 25, 'humidity_threshold' => 60]);
        Sensor::factory()->create(['lab_room_name' => 'FESEM Room Chiller', 'temp_threshold' => 20, 'humidity_threshold' => 60]);
    }
}
