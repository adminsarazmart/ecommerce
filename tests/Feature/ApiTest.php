<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Tests\TestCase;

class ApiTest extends TestCase
{
    public function test_api_products_list(): void
    {
        Product::factory(3)->active()->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_api_product_detail(): void
    {
        $product = Product::factory()->active()->create();

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_api_categories(): void
    {
        Category::factory(3)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_api_category_tree(): void
    {
        $parent = Category::factory()->create(['parent_id' => null]);
        Category::factory(2)->create(['parent_id' => $parent->id]);

        $response = $this->getJson('/api/v1/categories/tree');

        $response->assertStatus(200);
    }

    public function test_api_search(): void
    {
        Product::factory()->active()->create(['name' => 'Searchable Product']);

        $response = $this->getJson('/api/v1/search?q=Searchable');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_api_login(): void
    {
        User::factory()->create([
            'email' => 'api-test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'api-test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => ['token']]);
    }

    public function test_api_login_fails_with_wrong_credentials(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_api_registration(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'API User',
            'email' => 'api-new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);
    }

    public function test_api_authenticated_routes_require_auth(): void
    {
        $response = $this->getJson('/api/v1/cart');

        $response->assertStatus(401);
    }

    public function test_api_authenticated_user_can_access_cart(): void
    {
        $user = User::factory()->create();
        Customer::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/cart');

        $response->assertStatus(200);
    }

    public function test_api_vendors_list(): void
    {
        $response = $this->getJson('/api/v1/vendors');

        $response->assertStatus(200);
    }

    public function test_api_product_not_found(): void
    {
        $response = $this->getJson('/api/v1/products/99999');

        $response->assertStatus(404);
    }

    public function test_api_search_requires_min_length(): void
    {
        $response = $this->getJson('/api/v1/search?q=a');

        $response->assertStatus(400);
    }
}
