<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DoorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room' => fake()->numberBetween(1, 1000),
            'building' => fake()->numberBetween(1, 30),
            'level' => fake()->numberBetween(1, 5)
        ];
    }
}
