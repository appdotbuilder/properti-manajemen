<?php

namespace Database\Factories;

use App\Models\House;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\House>
 */
class HouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Type 36', 'Type 45', 'Type 54', 'Type 60', 'Type 70'];
        $statuses = ['available', 'sold', 'occupied'];
        
        return [
            'address' => fake()->streetAddress() . ', ' . fake()->city(),
            'type' => fake()->randomElement($types),
            'land_area' => fake()->randomFloat(2, 50, 200),
            'building_area' => fake()->randomFloat(2, 30, 150),
            'status' => fake()->randomElement($statuses),
            'owner_name' => fake()->name(),
            'owner_phone' => fake()->phoneNumber(),
            'handover_date' => fake()->optional(0.6)->dateTimeBetween('-2 years', 'now'),
            'price' => fake()->numberBetween(200000000, 1500000000),
            'bedrooms' => fake()->numberBetween(2, 4),
            'bathrooms' => fake()->numberBetween(1, 3),
            'block_unit' => 'A' . fake()->unique()->numberBetween(1, 200),
            'description' => fake()->optional(0.7)->sentence(10),
        ];
    }

    /**
     * Indicate that the house is available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
            'owner_name' => null,
            'owner_phone' => null,
            'handover_date' => null,
        ]);
    }

    /**
     * Indicate that the house is sold.
     */
    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sold',
            'handover_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the house is occupied.
     */
    public function occupied(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'occupied',
            'handover_date' => fake()->dateTimeBetween('-2 years', 'now'),
        ]);
    }
}