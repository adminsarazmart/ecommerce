<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(rand(2, 4), true);

        return [
            'vendor_id' => Vendor::factory(),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'sku' => strtoupper(Str::random(10)),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(),
            'unit_price' => fake()->randomFloat(2, 10, 1000),
            'sale_price' => fake()->boolean(30) ? fake()->randomFloat(2, 5, 800) : null,
            'cost_price' => fake()->randomFloat(2, 5, 500),
            'wholesale_price' => fake()->boolean(20) ? fake()->randomFloat(2, 8, 700) : null,
            'min_wholesale_qty' => fake()->boolean(20) ? fake()->numberBetween(5, 50) : null,
            'tax' => fake()->randomFloat(2, 0, 15),
            'tax_type' => fake()->randomElement(['percentage', 'fixed']),
            'weight' => fake()->randomFloat(2, 0.1, 50),
            'height' => fake()->randomFloat(2, 1, 100),
            'width' => fake()->randomFloat(2, 1, 100),
            'length' => fake()->randomFloat(2, 1, 100),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->sentence(),
            'tags' => fake()->words(5),
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
            'is_trending' => fake()->boolean(15),
            'is_new' => fake()->boolean(25),
            'allow_backorder' => fake()->boolean(10),
            'min_qty' => 1,
            'max_qty' => 100,
            'total_ratings' => fake()->numberBetween(0, 5),
            'total_reviews' => fake()->numberBetween(0, 50),
            'total_sales' => fake()->numberBetween(0, 500),
            'total_wishlist' => fake()->numberBetween(0, 100),
            'is_virtual' => fake()->boolean(5),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => true]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => ['is_featured' => true]);
    }

    public function onSale(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_price' => fake()->randomFloat(2, 5, 800),
        ]);
    }
}
