<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\House;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Kerusakan Pipa Air',
            'Lampu Jalan Mati',
            'Kebocoran Atap',
            'Kerusakan Listrik',
            'Masalah Saluran Air',
            'Kebisingan Berlebihan',
            'Kerusakan Gerbang',
            'Masalah Keamanan',
            'Kebersihan Area Umum',
            'Kerusakan Jalan',
        ];

        $categories = [
            'Listrik',
            'Air',
            'Infrastruktur',
            'Keamanan',
            'Kebersihan',
            'Maintenance',
        ];

        $statuses = ['new', 'in_progress', 'completed', 'rejected'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        return [
            'house_id' => House::factory(),
            'resident_id' => null, // Will be set by seeder
            'assigned_to' => null,
            'title' => fake()->randomElement($titles),
            'description' => fake()->paragraph(3),
            'status' => fake()->randomElement($statuses),
            'priority' => fake()->randomElement($priorities),
            'category' => fake()->randomElement($categories),
            'attachments' => fake()->optional(0.3)->randomElements(['image1.jpg', 'image2.jpg'], random_int(1, 2)),
            'response' => fake()->optional(0.4)->paragraph(),
            'resolved_at' => fake()->optional(0.3)->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the complaint is new.
     */
    public function newComplaint(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'new',
            'assigned_to' => null,
            'response' => null,
            'resolved_at' => null,
        ]);
    }

    /**
     * Indicate that the complaint is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'response' => fake()->optional(0.8)->paragraph(),
            'resolved_at' => null,
        ]);
    }

    /**
     * Indicate that the complaint is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'response' => fake()->paragraph(),
            'resolved_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}