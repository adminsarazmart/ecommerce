<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $vendors = Vendor::all();
        $products = Product::all();
        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'paid', 'paid', 'failed', 'refunded'];

        foreach (range(1, 25) as $i) {
            $customer = $customers->random();
            $vendor = $vendors->random();
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $itemCount = rand(1, 4);
            $subtotal = 0;
            $items = [];

            foreach (range(1, $itemCount) as $j) {
                $product = $products->random();
                $qty = rand(1, 3);
                $price = $product->sale_price ?? $product->unit_price;
                $items[] = ['product' => $product, 'qty' => $qty, 'price' => $price];
                $subtotal += $price * $qty;
            }

            $shipping = fake()->randomFloat(2, 0, 50);
            $tax = $subtotal * 0.1;
            $discount = $subtotal > 500 ? fake()->randomFloat(2, 10, 50) : 0;
            $grandTotal = $subtotal + $shipping + $tax - $discount;

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'customer_id' => $customer->id,
                'vendor_id' => $vendor->id,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'tax_amount' => $tax,
                'discount' => $discount,
                'grand_total' => max(0, $grandTotal),
                'paid_amount' => $paymentStatus === 'paid' ? $grandTotal : 0,
                'due_amount' => $paymentStatus === 'pending' ? $grandTotal : 0,
                'currency' => 'USD',
                'exchange_rate' => 1,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'shipping_status' => in_array($status, ['shipped', 'delivered']) ? 'shipped' : 'pending',
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
                    'country' => 'Bangladesh',
                ],
                'billing_address' => [
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'phone' => fake()->phoneNumber(),
                    'address_line1' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'zip' => fake()->postcode(),
                    'country' => 'Bangladesh',
                ],
                'notes' => fake()->boolean(20) ? fake()->sentence() : null,
                'is_seen' => fake()->boolean(60),
                'placed_at' => now()->subDays(rand(1, 30)),
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'vendor_id' => $vendor->id,
                    'product_name' => $item['product']->name,
                    'product_sku' => $item['product']->sku,
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['qty'],
                    'cost_price' => $item['product']->cost_price,
                    'commission_rate' => $vendor->commission_rate ?? 10,
                    'commission_amount' => ($item['price'] * $item['qty']) * ($vendor->commission_rate ?? 10) / 100,
                    'vendor_earnings' => ($item['price'] * $item['qty']) - (($item['price'] * $item['qty']) * ($vendor->commission_rate ?? 10) / 100),
                    'is_refunded' => false,
                    'refund_qty' => 0,
                ]);
            }

            if ($status === 'delivered') {
                Customer::where('id', $customer->id)->increment('total_orders');
                Customer::where('id', $customer->id)->increment('total_spent', $grandTotal);
            }
        }
    }
}
