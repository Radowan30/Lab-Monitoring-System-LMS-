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
        // Create or update admin user (prevents duplicate entry errors)
        User::updateOrCreate(
            ['email' => 'admin@lab.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        // Generate 4 random lab technicians only if they don't exist
        if (User::count() <= 1) { // Only create if we only have the admin user
            User::factory(4)->create(); // Generates 4 random users
        }

        // Seed related tables only if they're empty
        $this->call([
            SensorSeeder::class,
            CustomerSeeder::class,
            SensorsDataSeeder::class,
        ]);
    }
}
