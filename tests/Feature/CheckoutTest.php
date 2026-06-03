<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    public function test_guest_cannot_access_checkout(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_checkout_redirects_if_cart_empty(): void
    {
        $this->signInAsCustomer();

        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }

    public function test_customer_can_view_checkout_with_cart(): void
    {
        $user = $this->signInAsCustomer();
        $customer = $user->customer;

        $cart = Cart::create(['customer_id' => $customer->id]);
        $product = Product::factory()->active()->create(['unit_price' => 100]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Checkout/Index'));
    }

    public function test_customer_can_place_order(): void
    {
        $user = $this->signInAsCustomer();
        $customer = $user->customer;

        $cart = Cart::create(['customer_id' => $customer->id]);
        $product = Product::factory()->active()->create(['unit_price' => 100]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->post(route('checkout.store'), [
            'shipping_address' => [
                'first_name' => 'Test',
                'last_name' => 'User',
                'phone' => '01700000000',
                'address_line1' => '123 Test Street',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'zip' => '1200',
                'country' => 'Bangladesh',
            ],
            'payment' => [
                'method' => 'cod',
            ],
        ]);

        $response->assertRedirect();
    }

    public function test_checkout_validates_shipping_address(): void
    {
        $user = $this->signInAsCustomer();
        $customer = $user->customer;

        $cart = Cart::create(['customer_id' => $customer->id]);
        $product = Product::factory()->active()->create();

        $cart->items()->create(['product_id' => $product->id, 'quantity' => 1]);

        $response = $this->post(route('checkout.store'), [
            'shipping_address' => ['first_name' => 'Test'],
            'payment' => ['method' => 'cod'],
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_checkout_fails_without_stock(): void
    {
        $user = $this->signInAsCustomer();
        $customer = $user->customer;

        $cart = Cart::create(['customer_id' => $customer->id]);
        $product = Product::factory()->create(['is_active' => false]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->post(route('checkout.store'), [
            'shipping_address' => [
                'first_name' => 'Test', 'phone' => '01700000000',
                'address_line1' => 'Addr', 'city' => 'Dhaka', 'country' => 'Bangladesh',
            ],
            'payment' => ['method' => 'cod'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
