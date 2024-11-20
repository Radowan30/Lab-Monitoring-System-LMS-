<?php

namespace Database\Seeders;

use App\Models\SensorsData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SensorsData::factory()->count(50)->create();
    }
}
