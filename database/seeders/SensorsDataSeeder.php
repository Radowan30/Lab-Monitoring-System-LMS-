<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorsData;

class SensorsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if no sensor data exists
        if (SensorsData::count() === 0) {
            // Generate 50 random records
            SensorsData::factory()->count(50)->create();
        }
    }
}
