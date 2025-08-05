<?php

namespace Database\Factories;

use App\Models\House;
use App\Models\Payment;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Iuran Bulanan', 'Listrik', 'Air', 'Keamanan', 'Kebersihan', 'Maintenance'];
        $statuses = ['pending', 'paid', 'overdue'];
        
        $dueDate = fake()->dateTimeBetween('-3 months', '+2 months');
        $paymentDate = fake()->dateTimeBetween($dueDate, 'now');
        
        return [
            'house_id' => House::factory(),
            'resident_id' => null, // Will be set by seeder
            'payment_date' => $paymentDate,
            'amount' => fake()->numberBetween(50000, 500000),
            'type' => fake()->randomElement($types),
            'status' => fake()->randomElement($statuses),
            'due_date' => $dueDate,
            'description' => fake()->optional(0.6)->sentence(),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the payment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_date' => fake()->dateTimeBetween('now', '+1 month'),
        ]);
    }

    /**
     * Indicate that the payment is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'payment_date' => fake()->dateTimeBetween('-2 months', 'now'),
        ]);
    }

    /**
     * Indicate that the payment is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => fake()->dateTimeBetween('-2 months', '-1 day'),
            'payment_date' => fake()->dateTimeBetween('now', '+1 month'),
        ]);
    }
}