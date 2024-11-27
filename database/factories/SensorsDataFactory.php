<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SensorsData>
 */
class SensorsDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sensor_id' => $this->faker->numberBetween(1, 5),
            'temperature' => $this->faker->randomFloat(2, 18, 26),
            'humidity' => $this->faker->randomFloat(1, 50, 70),
            'recorded_at' => $this->faker->dateTimeThisMonth,
        ];
    }
}
