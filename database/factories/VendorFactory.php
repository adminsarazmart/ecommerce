<?php

namespace Database\Factories;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        $storeName = fake()->company();

        return [
            'user_id' => User::factory(),
            'store_name' => $storeName,
            'slug' => Str::slug($storeName) . '-' . Str::random(4),
            'store_description' => fake()->paragraph(),
            'store_email' => fake()->companyEmail(),
            'store_phone' => fake()->phoneNumber(),
            'store_address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip' => fake()->postcode(),
            'country' => fake()->country(),
            'commission_rate' => fake()->randomFloat(1, 5, 20),
            'commission_type' => 'percentage',
            'verification_status' => fake()->randomElement(['verified', 'unverified', 'pending']),
            'kyc_status' => fake()->randomElement(['approved', 'pending', 'rejected', 'not_submitted']),
            'kyc_documents' => null,
            'tax_id' => fake()->bothify('TAX-####-####'),
            'business_registration' => fake()->bothify('BR-####-####'),
            'website' => fake()->url(),
            'facebook' => 'https://facebook.com/' . fake()->userName(),
            'twitter' => 'https://twitter.com/' . fake()->userName(),
            'instagram' => 'https://instagram.com/' . fake()->userName(),
            'is_active' => true,
            'is_featured' => fake()->boolean(15),
            'total_ratings' => fake()->numberBetween(0, 5),
            'total_products' => fake()->numberBetween(0, 100),
            'total_sales' => fake()->numberBetween(0, 1000),
            'revenue' => fake()->randomFloat(2, 1000, 100000),
            'join_date' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => ['verification_status' => 'verified']);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => true]);
    }
}
