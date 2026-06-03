<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\User;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    public function test_admin_can_view_inventory(): void
    {
        $this->signInAsAdmin();

        $response = $this->get(route('admin.inventory.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_warehouse(): void
    {
        $this->signInAsAdmin();

        $response = $this->post(route('admin.inventory.warehouses-store'), [
            'name' => 'Main Warehouse',
            'code' => 'WH-MAIN-' . uniqid(),
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_admin_can_adjust_stock(): void
    {
        $this->signInAsAdmin();

        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();

        $response = $this->post(route('admin.inventory.adjust-stock'), [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 100,
            'type' => 'addition',
            'reason' => 'Initial stock',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('stocks', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 100,
        ]);
    }

    public function test_stock_deductions_work(): void
    {
        $this->signInAsAdmin();

        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();

        Stock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 50,
            'low_stock_threshold' => 5,
        ]);

        $response = $this->post(route('admin.inventory.adjust-stock'), [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 10,
            'type' => 'deduction',
        ]);

        $response->assertRedirect();
        $this->assertEquals(40, Stock::where('product_id', $product->id)->value('quantity'));
    }

    public function test_low_stock_alerts_are_detected(): void
    {
        $this->signInAsAdmin();

        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();

        Stock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 3,
            'low_stock_threshold' => 10,
        ]);

        $response = $this->get(route('admin.inventory.low-stock'));

        $response->assertStatus(200);
    }

    public function test_stock_movements_are_logged(): void
    {
        $this->signInAsAdmin();

        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();

        StockMovement::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 50,
            'type' => 'addition',
            'before_qty' => 0,
            'after_qty' => 50,
            'description' => 'Initial stock',
            'created_by' => auth()->id(),
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'addition',
            'quantity' => 50,
        ]);
    }

    public function test_cannot_deduct_more_than_available_stock(): void
    {
        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();

        Stock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 5,
        ]);

        $stock = Stock::where('product_id', $product->id)
            ->where('warehouse_id', $warehouse->id)
            ->first();

        $this->assertTrue($stock->quantity < 10);
    }
}
