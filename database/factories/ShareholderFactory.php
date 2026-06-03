<?php

namespace Database\Factories;

use App\Models\Shareholder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShareholderFactory extends Factory
{
    protected $model = Shareholder::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'shareholder_code' => 'SH-' . strtoupper(Str::random(6)),
            'share_percentage' => fake()->randomFloat(2, 1, 30),
            'total_investment' => fake()->randomFloat(2, 50000, 5000000),
            'total_shares' => fake()->numberBetween(100, 10000),
            'join_date' => fake()->dateTimeBetween('-3 years', 'now'),
            'status' => 'active',
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'active']);
    }
}
