<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_orders' => fake()->numberBetween(0, 50),
            'total_spent' => fake()->randomFloat(2, 0, 5000),
            'loyalty_points' => fake()->numberBetween(0, 1000),
            'wallet_balance' => fake()->randomFloat(2, 0, 500),
            'membership_level' => fake()->randomElement(['regular', 'silver', 'gold', 'platinum']),
            'referred_by' => null,
        ];
    }

    public function withUser(): static
    {
        return $this->state(function (array $attributes) {
            $user = User::factory()->create();
            $user->assignRole('Customer');
            return ['user_id' => $user->id];
        });
    }

    public function regular(): static
    {
        return $this->state(fn (array $attributes) => ['membership_level' => 'regular']);
    }

    public function gold(): static
    {
        return $this->state(fn (array $attributes) => ['membership_level' => 'gold']);
    }

    public function platinum(): static
    {
        return $this->state(fn (array $attributes) => ['membership_level' => 'platinum']);
    }
}
