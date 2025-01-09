<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sensor>
 */
class SensorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lab_room_name' => $this->faker->randomElement([
            'Preparation Lab', 'FETEM Room', 'FETEM Room Chiller', 'FESEM Room', 'FESEM Room Chiller'
            ]),
            'temp_threshold' => $this->faker->randomFloat(2, 20, 25),
            'humidity_threshold' => 60,
        ];
    }
}
