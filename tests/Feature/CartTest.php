<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;

class CartTest extends TestCase
{
    public function test_guest_cannot_access_cart_actions(): void
    {
        $response = $this->post(route('cart.add'), ['product_id' => 1, 'quantity' => 1]);

        $response->assertRedirect(route('login'));
    }

    public function test_customer_can_add_to_cart(): void
    {
        $this->signInAsCustomer();

        $product = Product::factory()->active()->create();

        $response = $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_customer_can_view_cart(): void
    {
        $this->signInAsCustomer();

        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Cart/Index'));
    }

    public function test_customer_can_update_cart_quantity(): void
    {
        $user = $this->signInAsCustomer();
        $customer = $user->customer;

        $cart = Cart::create(['customer_id' => $customer->id]);
        $product = Product::factory()->create();

        $item = $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->post(route('cart.update', $item->id), ['quantity' => 3]);

        $response->assertRedirect();
        $this->assertEquals(3, $item->fresh()->quantity);
    }

    public function test_customer_can_remove_from_cart(): void
    {
        $user = $this->signInAsCustomer();
        $customer = $user->customer;

        $cart = Cart::create(['customer_id' => $customer->id]);
        $product = Product::factory()->create();

        $item = $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->delete(route('cart.remove', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_cart_is_empty_for_new_customer(): void
    {
        $user = $this->signInAsCustomer();

        $response = $this->get(route('cart.index'));

        $response->assertInertia(fn ($page) => $page->component('Cart/Index'));
    }

    public function test_cart_totals_are_calculated_correctly(): void
    {
        $user = $this->signInAsCustomer();
        $customer = $user->customer;

        $cart = Cart::create(['customer_id' => $customer->id]);

        $product1 = Product::factory()->create(['unit_price' => 100]);
        $product2 = Product::factory()->create(['unit_price' => 200]);

        $cart->items()->createMany([
            ['product_id' => $product1->id, 'quantity' => 2],
            ['product_id' => $product2->id, 'quantity' => 1],
        ]);

        $cart->load('items.product');

        $subtotal = $cart->items->sum(fn ($item) => $item->product->unit_price * $item->quantity);

        $this->assertEquals(400, $subtotal);
    }
}
