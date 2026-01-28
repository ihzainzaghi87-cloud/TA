<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'subtotal' => fake()->randomFloat(2, 50000, 500000),
            'shipping_cost' => fake()->randomFloat(2, 10000, 50000),
            'total' => fake()->randomFloat(2, 60000, 550000),
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ];
    }
}
