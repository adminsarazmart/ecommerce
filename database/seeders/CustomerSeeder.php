<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Address;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        foreach ($customers as $customer) {
            if (fake()->boolean(70)) {
                Address::create([
                    'customer_id' => $customer->id,
                    'label' => 'Home',
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'phone' => fake()->phoneNumber(),
                    'address_line1' => fake()->streetAddress(),
                    'city' => 'Dhaka',
                    'state' => 'Dhaka',
                    'zip' => fake()->postcode(),
                    'country' => 'Bangladesh',
                    'is_default' => true,
                    'is_shipping' => true,
                    'is_billing' => true,
                ]);
            }

            if (fake()->boolean(40)) {
                Address::create([
                    'customer_id' => $customer->id,
                    'label' => 'Office',
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'phone' => fake()->phoneNumber(),
                    'address_line1' => fake()->streetAddress(),
                    'city' => 'Chittagong',
                    'state' => 'Chittagong',
                    'zip' => fake()->postcode(),
                    'country' => 'Bangladesh',
                    'is_default' => false,
                    'is_shipping' => true,
                    'is_billing' => false,
                ]);
            }

            $wishlistCount = rand(2, 6);
            $added = [];
            foreach (range(1, $wishlistCount) as $i) {
                $product = $products->random();
                if (!in_array($product->id, $added)) {
                    Wishlist::create([
                        'customer_id' => $customer->id,
                        'product_id' => $product->id,
                    ]);
                    $added[] = $product->id;
                }
            }
        }
    }
}
