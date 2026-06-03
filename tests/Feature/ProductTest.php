<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class ProductTest extends TestCase
{
    public function test_guest_can_view_products_list(): void
    {
        Product::factory(3)->active()->create();

        $response = $this->get(route('shop.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Shop/Index')
            ->has('products.data')
        );
    }

    public function test_guest_can_view_product_detail(): void
    {
        $product = Product::factory()->active()->create();

        $response = $this->get(route('shop.product', $product->slug));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Shop/Show')
            ->has('product')
        );
    }

    public function test_guest_cannot_view_inactive_product(): void
    {
        $product = Product::factory()->create(['is_active' => false]);

        $response = $this->get(route('shop.product', $product->slug));

        $response->assertStatus(404);
    }

    public function test_admin_can_create_product(): void
    {
        $this->signInAsAdmin();

        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        $response = $this->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'sku' => 'TST-' . uniqid(),
            'unit_price' => 99.99,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_product_creation_validation_fails(): void
    {
        $this->signInAsAdmin();

        $response = $this->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors(['name', 'sku', 'unit_price']);
    }

    public function test_admin_can_update_product(): void
    {
        $this->signInAsAdmin();

        $product = Product::factory()->create();

        $response = $this->put(route('admin.products.update', $product), [
            'name' => 'Updated Product Name',
            'sku' => $product->sku,
            'unit_price' => 149.99,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Updated Product Name']);
    }

    public function test_admin_can_delete_product(): void
    {
        $this->signInAsAdmin();

        $product = Product::factory()->create();

        $response = $this->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertSoftDeleted($product);
    }

    public function test_admin_can_bulk_delete_products(): void
    {
        $this->signInAsAdmin();

        $products = Product::factory(3)->create();

        $response = $this->post(route('admin.products.bulk-action'), [
            'action' => 'delete',
            'ids' => $products->pluck('id')->toArray(),
        ]);

        $response->assertRedirect();
        foreach ($products as $product) {
            $this->assertSoftDeleted($product);
        }
    }

    public function test_vendor_can_only_see_own_products(): void
    {
        $vendor = $this->signInAsVendor();
        $vendorModel = $vendor->vendor;

        $ownProduct = Product::factory()->create(['vendor_id' => $vendorModel->id]);
        $otherProduct = Product::factory()->create();

        $this->get(route('vendor.products.index'))
            ->assertInertia(fn ($page) => $page
                ->component('Vendor/Products/Index')
                ->has('products.data', 1)
            );
    }

    public function test_product_search_works(): void
    {
        Product::factory()->active()->create(['name' => 'Unique Gaming Laptop']);
        Product::factory(3)->active()->create();

        $response = $this->get(route('shop.index', ['search' => 'Gaming']));

        $response->assertStatus(200);
    }

    public function test_product_filtering_by_category(): void
    {
        $category = Category::factory()->create();
        Product::factory(2)->active()->create(['category_id' => $category->id]);
        Product::factory(3)->active()->create();

        $response = $this->get(route('shop.index', ['category' => $category->slug]));

        $response->assertStatus(200);
    }

    public function test_product_variant_management(): void
    {
        $this->signInAsAdmin();

        $product = Product::factory()->create();

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Large',
            'sku' => $product->sku . '-L',
            'price' => 129.99,
            'stock' => 50,
        ]);

        $this->assertDatabaseHas('product_variants', ['sku' => $product->sku . '-L']);
        $this->assertEquals(50, $variant->stock);
    }
}
