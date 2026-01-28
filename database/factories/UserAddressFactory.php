<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
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
            'label' => fake()->randomElement(['Home', 'Office', 'Other']),
            'recipient_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'province_id' => fake()->numberBetween(1, 34),
            'province_name' => fake()->state(),
            'city_id' => fake()->numberBetween(1, 501),
            'city_name' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'is_primary' => false,
            'is_active' => true,
        ];
    }
}
