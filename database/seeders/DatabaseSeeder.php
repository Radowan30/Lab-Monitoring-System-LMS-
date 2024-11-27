<?php

namespace Database\Seeders;

use Database\Seeders\SensorSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\SensorDataSeeder;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create one admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@lab.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        // Generate 4 random lab technicians
        User::factory(4)->create(); // Generates 4 random users

        // Seed related tables
        $this->call([
            SensorSeeder::class,
            CustomerSeeder::class,
            SensorsDataSeeder::class,
        ]);
    }
}
