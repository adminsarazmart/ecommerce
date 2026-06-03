<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = Vendor::all();
        $categories = Category::whereNotNull('parent_id')->get();
        $brands = Brand::all();

        $products = [
            ['name' => 'iPhone 15 Pro Max', 'price' => 1499.99, 'sale' => 1399.99, 'cost' => 1100.00],
            ['name' => 'Samsung Galaxy S24 Ultra', 'price' => 1299.99, 'sale' => 1199.99, 'cost' => 950.00],
            ['name' => 'MacBook Pro 16" M3', 'price' => 2499.99, 'sale' => null, 'cost' => 1900.00],
            ['name' => 'Dell XPS 15', 'price' => 1899.99, 'sale' => 1699.99, 'cost' => 1400.00],
            ['name' => 'Sony WH-1000XM5 Headphones', 'price' => 399.99, 'sale' => 349.99, 'cost' => 250.00],
            ['name' => 'Apple AirPods Pro 2', 'price' => 249.99, 'sale' => 229.99, 'cost' => 170.00],
            ['name' => 'Samsung Galaxy Watch 6', 'price' => 399.99, 'sale' => 349.99, 'cost' => 280.00],
            ['name' => 'Nike Air Max 270', 'price' => 189.99, 'sale' => null, 'cost' => 110.00],
            ['name' => 'Adidas Ultraboost 23', 'price' => 219.99, 'sale' => 179.99, 'cost' => 130.00],
            ['name' => 'Sony PlayStation 5', 'price' => 699.99, 'sale' => null, 'cost' => 500.00],
            ['name' => 'LG 65" OLED TV', 'price' => 2499.99, 'sale' => 2199.99, 'cost' => 1800.00],
            ['name' => 'Samsung 75" QLED TV', 'price' => 2999.99, 'sale' => 2699.99, 'cost' => 2100.00],
            ['name' => 'iPhone 15', 'price' => 999.99, 'sale' => 949.99, 'cost' => 750.00],
            ['name' => 'Samsung Galaxy Tab S9', 'price' => 899.99, 'sale' => 849.99, 'cost' => 650.00],
            ['name' => 'iPad Pro 12.9"', 'price' => 1299.99, 'sale' => null, 'cost' => 950.00],
            ['name' => 'Lenovo ThinkPad X1 Carbon', 'price' => 1799.99, 'sale' => 1599.99, 'cost' => 1300.00],
            ['name' => 'HP Spectre x360', 'price' => 1599.99, 'sale' => 1399.99, 'cost' => 1150.00],
            ['name' => 'Canon EOS R5', 'price' => 3899.99, 'sale' => 3599.99, 'cost' => 2900.00],
            ['name' => 'Nikon Z8', 'price' => 3999.99, 'sale' => null, 'cost' => 3000.00],
            ['name' => 'Bose QuietComfort Earbuds II', 'price' => 299.99, 'sale' => 279.99, 'cost' => 200.00],
            ['name' => 'Levi\'s 501 Original Jeans', 'price' => 89.99, 'sale' => null, 'cost' => 45.00],
            ['name' => 'Ray-Ban Aviator Sunglasses', 'price' => 199.99, 'sale' => 169.99, 'cost' => 90.00],
            ['name' => 'Rolex Submariner Watch', 'price' => 9950.00, 'sale' => null, 'cost' => 6000.00],
            ['name' => 'Michael Kors Jet Set Bag', 'price' => 298.00, 'sale' => 249.00, 'cost' => 150.00],
            ['name' => 'KitchenAid Stand Mixer', 'price' => 499.99, 'sale' => 449.99, 'cost' => 300.00],
            ['name' => 'Dyson V15 Detect Vacuum', 'price' => 799.99, 'sale' => 749.99, 'cost' => 500.00],
            ['name' => 'Nespresso Vertuo Coffee Machine', 'price' => 299.99, 'sale' => 269.99, 'cost' => 180.00],
            ['name' => 'IKEA MALM Bed Frame', 'price' => 499.00, 'sale' => null, 'cost' => 280.00],
            ['name' => 'Harry Potter Box Set', 'price' => 89.99, 'sale' => 69.99, 'cost' => 40.00],
            ['name' => 'Atomic Habits Book', 'price' => 24.99, 'sale' => null, 'cost' => 12.00],
            ['name' => 'Think and Grow Rich', 'price' => 19.99, 'sale' => 14.99, 'cost' => 8.00],
            ['name' => 'Yoga Mat Premium', 'price' => 49.99, 'sale' => 39.99, 'cost' => 20.00],
            ['name' => 'Dumbbell Set 20kg', 'price' => 149.99, 'sale' => null, 'cost' => 80.00],
            ['name' => 'Treadmill Pro', 'price' => 1299.99, 'sale' => 999.99, 'cost' => 700.00],
            ['name' => 'L\'Oreal Paris Skincare Set', 'price' => 79.99, 'sale' => 59.99, 'cost' => 35.00],
            ['name' => 'MAC Lipstick Collection', 'price' => 45.00, 'sale' => null, 'cost' => 22.00],
            ['name' => 'Chanel No. 5 Perfume', 'price' => 135.00, 'sale' => 125.00, 'cost' => 70.00],
            ['name' => 'Car Dash Camera 4K', 'price' => 199.99, 'sale' => 169.99, 'cost' => 100.00],
            ['name' => 'Michelin Tyres Set', 'price' => 899.99, 'sale' => null, 'cost' => 550.00],
            ['name' => 'Organic Rice 5kg', 'price' => 24.99, 'sale' => 19.99, 'cost' => 12.00],
            ['name' => 'Premium Olive Oil 1L', 'price' => 29.99, 'sale' => null, 'cost' => 15.00],
            ['name' => 'Green Tea Collection', 'price' => 18.99, 'sale' => 14.99, 'cost' => 8.00],
            ['name' => 'Wireless Gaming Mouse', 'price' => 79.99, 'sale' => 59.99, 'cost' => 35.00],
            ['name' => 'Mechanical Keyboard RGB', 'price' => 149.99, 'sale' => 129.99, 'cost' => 75.00],
            ['name' => '4K Webcam for Streaming', 'price' => 199.99, 'sale' => null, 'cost' => 110.00],
            ['name' => 'External SSD 1TB', 'price' => 129.99, 'sale' => 109.99, 'cost' => 65.00],
            ['name' => 'Bluetooth Speaker Waterproof', 'price' => 89.99, 'sale' => 69.99, 'cost' => 40.00],
            ['name' => 'Smart Home Security Camera', 'price' => 59.99, 'sale' => 49.99, 'cost' => 25.00],
            ['name' => 'Electric Toothbrush', 'price' => 79.99, 'sale' => null, 'cost' => 40.00],
            ['name' => 'Hair Dryer Professional', 'price' => 129.99, 'sale' => 99.99, 'cost' => 60.00],
            ['name' => 'Portable Power Bank 20000mAh', 'price' => 49.99, 'sale' => 39.99, 'cost' => 22.00],
            ['name' => 'Standing Desk Adjustable', 'price' => 499.99, 'sale' => 449.99, 'cost' => 280.00],
            ['name' => 'Ergonomic Office Chair', 'price' => 399.99, 'sale' => 349.99, 'cost' => 200.00],
            ['name' => 'Air Purifier for Home', 'price' => 299.99, 'sale' => 269.99, 'cost' => 160.00],
        ];

        foreach ($products as $i => $productData) {
            $category = $categories->random();
            $brand = $brands->random();
            $vendor = $vendors->random();

            $sku = strtoupper(Str::random(8));

            $product = Product::create([
                'vendor_id' => $vendor->id,
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']) . '-' . $sku,
                'sku' => $sku,
                'description' => fake()->paragraphs(3, true),
                'short_description' => "Premium {$productData['name']} at best price",
                'unit_price' => $productData['price'],
                'sale_price' => $productData['sale'],
                'cost_price' => $productData['cost'],
                'tax' => 10,
                'tax_type' => 'percentage',
                'weight' => fake()->randomFloat(2, 0.1, 10),
                'is_active' => true,
                'is_featured' => $i < 10,
                'is_trending' => $i < 15 && $i >= 5,
                'is_new' => $i >= 45,
                'min_qty' => 1,
                'max_qty' => 50,
                'total_sales' => fake()->numberBetween(10, 500),
                'total_ratings' => fake()->numberBetween(3, 5),
                'total_reviews' => fake()->numberBetween(1, 50),
            ]);

            if ($i % 5 === 0 && $productData['sale']) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => 'Default',
                    'sku' => $sku . '-DEFAULT',
                    'price' => $productData['sale'],
                    'stock' => fake()->numberBetween(10, 100),
                ]);
            }
        }
    }
}
