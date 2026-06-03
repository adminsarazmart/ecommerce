<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 2000);
        $shipping = fake()->randomFloat(2, 0, 100);
        $tax = $subtotal * 0.1;
        $discount = fake()->boolean(30) ? fake()->randomFloat(2, 5, 100) : 0;
        $grandTotal = $subtotal + $shipping + $tax - $discount;

        return [
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'customer_id' => Customer::factory(),
            'vendor_id' => Vendor::factory(),
            'coupon_id' => fake()->boolean(20) ? Coupon::factory() : null,
            'coupon_discount' => 0,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'tax_amount' => $tax,
            'discount' => $discount,
            'grand_total' => max(0, $grandTotal),
            'paid_amount' => $grandTotal,
            'due_amount' => 0,
            'currency' => 'USD',
            'exchange_rate' => 1,
            'status' => fake()->randomElement(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled']),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed', 'refunded']),
            'shipping_status' => fake()->randomElement(['pending', 'shipped', 'delivered']),
            'payment_method' => fake()->randomElement(['stripe', 'paypal', 'bkash', 'cod']),
            'shipping_method' => 'standard',
            'shipping_address' => [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'phone' => fake()->phoneNumber(),
                'address_line1' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'zip' => fake()->postcode(),
                'country' => fake()->country(),
            ],
            'billing_address' => [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'phone' => fake()->phoneNumber(),
                'address_line1' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'zip' => fake()->postcode(),
                'country' => fake()->country(),
            ],
            'notes' => fake()->boolean(20) ? fake()->sentence() : null,
            'is_partial' => false,
            'partial_payments' => null,
            'is_seen' => fake()->boolean(70),
            'placed_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'confirmed_at' => now()->subDays(fake()->numberBetween(0, 28)),
            'processing_at' => now()->subDays(fake()->numberBetween(0, 26)),
            'shipped_at' => now()->subDays(fake()->numberBetween(0, 24)),
            'delivered_at' => now()->subDays(fake()->numberBetween(0, 20)),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'pending']);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'delivered', 'payment_status' => 'paid']);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'cancelled', 'payment_status' => 'refunded']);
    }
}
