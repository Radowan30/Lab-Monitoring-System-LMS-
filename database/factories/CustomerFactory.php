<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name,
            'passport_number' => $this->faker->unique()->lexify('?????-?????-?????'),
            'institution' => $this->faker->company,
            'position' => $this->faker->jobTitle,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'entry_datetime' => $this->faker->dateTimeThisYear,
            'exit_datetime' => $this->faker->dateTimeThisYear,
            'purpose_of_usage' => $this->faker->word,
            'purpose_description' => $this->faker->paragraph,
            'equipment_used' => $this->faker->word,
            'type_of_analysis' => $this->faker->sentence,
            'supervisor_name' => $this->faker->name,
            'usage_duration' => $this->faker->randomFloat(2, 1, 24),
            'suggestions' => $this->faker->sentence,
            'technical_issues' => $this->faker->sentence,
        ];
    }
}
