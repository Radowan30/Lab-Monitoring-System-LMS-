<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if no customers exist
        if (Customer::count() === 0) {
            try {
                // Try to use factory if Faker is available
                Customer::factory()->count(10)->create();
            } catch (\Exception $e) {
                // Fallback: create customers without factory if Faker is not available
                $this->createCustomersWithoutFactory();
            }
        }
    }

    /**
     * Create customers without using factories (fallback when Faker is not available)
     */
    private function createCustomersWithoutFactory(): void
    {
        $customers = [
            ['name' => 'Customer 1', 'email' => 'customer1@example.com', 'phone' => '123-456-7890'],
            ['name' => 'Customer 2', 'email' => 'customer2@example.com', 'phone' => '123-456-7891'],
            ['name' => 'Customer 3', 'email' => 'customer3@example.com', 'phone' => '123-456-7892'],
            ['name' => 'Customer 4', 'email' => 'customer4@example.com', 'phone' => '123-456-7893'],
            ['name' => 'Customer 5', 'email' => 'customer5@example.com', 'phone' => '123-456-7894'],
        ];

        foreach ($customers as $customerData) {
            Customer::updateOrCreate(
                ['email' => $customerData['email']],
                [
                    'name' => $customerData['name'],
                    'phone' => $customerData['phone'],
                ]
            );
        }
    }
}
