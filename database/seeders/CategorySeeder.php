<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics' => [
                'Mobile Phones', 'Laptops', 'Tablets', 'Headphones', 'Cameras',
                'Smart Watches', 'Computer Accessories', 'Gaming Consoles',
            ],
            'Fashion' => [
                "Men's Clothing", "Women's Clothing", "Kids' Fashion",
                'Shoes', 'Bags & Accessories', 'Jewelry', 'Watches',
            ],
            'Home & Garden' => [
                'Furniture', 'Kitchen Appliances', 'Home Decor', 'Bedding',
                'Gardening Tools', 'Lighting', 'Storage & Organization',
            ],
            'Books' => [
                'Fiction', 'Non-Fiction', 'Academic', 'Children Books',
                'Comics', 'Magazines', 'E-Books',
            ],
            'Sports' => [
                'Fitness Equipment', 'Sports Shoes', 'Sports Wear',
                'Cycling', 'Camping Gear', 'Team Sports',
            ],
            'Beauty' => [
                'Skincare', 'Makeup', 'Hair Care', 'Fragrances',
                'Personal Care', 'Beauty Tools',
            ],
            'Automotive' => [
                'Car Accessories', 'Motorcycle Parts', 'Car Care',
                'Tools & Equipment', 'Interior Accessories',
            ],
            'Grocery' => [
                'Rice & Grains', 'Cooking Oil', 'Spices', 'Beverages',
                'Snacks', 'Dairy', 'Meat & Fish', 'Fruits & Vegetables',
            ],
        ];

        $sortOrder = 0;
        foreach ($categories as $parentName => $children) {
            $parent = Category::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
                'description' => "Shop the best {$parentName} products",
                'icon' => $this->getIconForCategory($parentName),
                'sort_order' => $sortOrder++,
                'is_active' => true,
                'display_mode' => 'both',
            ]);

            foreach ($children as $childIndex => $childName) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'description' => "Browse our collection of {$childName}",
                    'sort_order' => $childIndex,
                    'is_active' => true,
                    'display_mode' => 'products',
                ]);
            }
        }
    }

    private function getIconForCategory(string $name): string
    {
        return match ($name) {
            'Electronics' => 'fas fa-laptop',
            'Fashion' => 'fas fa-tshirt',
            'Home & Garden' => 'fas fa-home',
            'Books' => 'fas fa-book',
            'Sports' => 'fas fa-futbol',
            'Beauty' => 'fas fa-paint-brush',
            'Automotive' => 'fas fa-car',
            'Grocery' => 'fas fa-shopping-basket',
            default => 'fas fa-tag',
        };
    }
}
