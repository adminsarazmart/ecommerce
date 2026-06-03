<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_admin_can_view_orders_list(): void
    {
        $this->signInAsAdmin();

        Order::factory(3)->create();

        $response = $this->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Orders/Index'));
    }

    public function test_admin_can_view_order_detail(): void
    {
        $this->signInAsAdmin();

        $order = Order::factory()->create();

        $response = $this->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
    }

    public function test_admin_can_update_order_status(): void
    {
        $this->signInAsAdmin();

        $order = Order::factory()->pending()->create();

        $response = $this->post(route('admin.orders.update-status', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect();
        $this->assertEquals('confirmed', $order->fresh()->status);
    }

    public function test_order_status_flow_is_valid(): void
    {
        $this->signInAsAdmin();

        $order = Order::factory()->create(['status' => 'pending']);

        $validTransitions = ['confirmed', 'processing', 'shipped', 'delivered'];
        $currentStatus = 'pending';

        foreach ($validTransitions as $newStatus) {
            $this->post(route('admin.orders.update-status', $order), ['status' => $newStatus]);
            $currentStatus = $newStatus;
        }

        $this->assertEquals('delivered', $order->fresh()->status);
    }

    public function test_admin_can_process_refund(): void
    {
        $this->signInAsAdmin();

        $order = Order::factory()->completed()->create();

        $response = $this->post(route('admin.orders.refund', $order), [
            'amount' => $order->grand_total,
            'reason' => 'Customer requested refund',
        ]);

        $response->assertRedirect();
        $this->assertEquals('refunded', $order->fresh()->status);
    }

    public function test_customer_can_view_own_orders(): void
    {
        $user = $this->signInAsCustomer();
        $customer = $user->customer;

        Order::factory(2)->create(['customer_id' => $customer->id]);
        Order::factory(3)->create();

        $this->get(route('account.orders'))
            ->assertInertia(fn ($page) => $page->component('Account/Orders'));
    }

    public function test_order_creation_fails_without_items(): void
    {
        $this->signInAsCustomer();

        $response = $this->post(route('checkout.store'), [
            'shipping_address' => [
                'first_name' => 'Test',
                'phone' => '01700000000',
                'address_line1' => 'Test Address',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
            ],
            'payment' => ['method' => 'cod'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_order_cancel_by_customer(): void
    {
        $user = $this->signInAsCustomer();
        $customer = $user->customer;

        $order = Order::factory()->pending()->create(['customer_id' => $customer->id]);

        $this->post(route('admin.orders.update-status', $order), ['status' => 'cancelled']);

        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    public function test_order_invoice_generation(): void
    {
        $this->signInAsAdmin();

        $order = Order::factory()->completed()->create();

        $response = $this->get(route('admin.orders.invoice', $order));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
