<?php

namespace Database\Factories;

use App\Models\House;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'house_id' => House::factory(),
            'user_id' => fake()->optional(0.7)->randomElement(User::residents()->pluck('id')),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional(0.8)->safeEmail(),
            'address' => fake()->optional(0.6)->address(),
            'move_in_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'move_out_date' => fake()->optional(0.1)->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement(['active', 'inactive']),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the resident is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'move_out_date' => null,
        ]);
    }

    /**
     * Indicate that the resident is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
            'move_out_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }
}