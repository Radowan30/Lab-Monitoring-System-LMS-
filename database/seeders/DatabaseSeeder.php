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
            try {
                // Try to use factory if Faker is available
                User::factory(4)->create();
            } catch (\Exception $e) {
                // Fallback: create users without factory if Faker is not available
                $this->createUsersWithoutFactory();
            }
        }

        // Seed related tables only if they're empty
        $this->call([
            SensorSeeder::class,
            CustomerSeeder::class,
            SensorsDataSeeder::class,
        ]);
    }

    /**
     * Create users without using factories (fallback when Faker is not available)
     */
    private function createUsersWithoutFactory(): void
    {
        $users = [
            ['name' => 'Lab Technician 1', 'email' => 'tech1@lab.com'],
            ['name' => 'Lab Technician 2', 'email' => 'tech2@lab.com'],
            ['name' => 'Lab Technician 3', 'email' => 'tech3@lab.com'],
            ['name' => 'Lab Technician 4', 'email' => 'tech4@lab.com'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'is_admin' => false,
                ]
            );
        }
    }
}
