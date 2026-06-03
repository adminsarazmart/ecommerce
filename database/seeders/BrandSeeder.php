<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'description' => 'Premium electronics and technology'],
            ['name' => 'Samsung', 'description' => 'Leading consumer electronics'],
            ['name' => 'Nike', 'description' => 'Athletic footwear and apparel'],
            ['name' => 'Adidas', 'description' => 'Sportswear and accessories'],
            ['name' => 'Sony', 'description' => 'Electronics and entertainment'],
            ['name' => 'LG', 'description' => 'Home appliances and electronics'],
            ['name' => 'Dell', 'description' => 'Computer technology'],
            ['name' => 'HP', 'description' => 'Printers and computing'],
            ['name' => 'Lenovo', 'description' => 'Computers and smart devices'],
            ['name' => 'Microsoft', 'description' => 'Software and hardware'],
            ['name' => 'Coca-Cola', 'description' => 'Beverages'],
            ['name' => 'Pepsi', 'description' => 'Beverages and snacks'],
            ['name' => 'Unilever', 'description' => 'Consumer goods'],
            ['name' => 'Procter & Gamble', 'description' => 'Consumer goods'],
            ['name' => 'Toyota', 'description' => 'Automotive'],
            ['name' => 'Honda', 'description' => 'Automotive and power equipment'],
            ['name' => 'BATA', 'description' => 'Footwear'],
            ['name' => 'ACI', 'description' => 'Bangladeshi conglomerate'],
            ['name' => 'Pran', 'description' => 'Bangladeshi food and beverage'],
            ['name' => 'Square', 'description' => 'Bangladeshi pharmaceuticals and consumer goods'],
            ['name' => 'RFL', 'description' => 'Bangladeshi plastics and electronics'],
            ['name' => 'Walton', 'description' => 'Bangladeshi electronics manufacturer'],
        ];

        foreach ($brands as $i => $brandData) {
            Brand::create([
                'name' => $brandData['name'],
                'slug' => Str::slug($brandData['name']),
                'description' => $brandData['description'],
                'website' => 'https://' . Str::slug($brandData['name']) . '.com',
                'is_active' => true,
                'sort_order' => $i,
            ]);
        }
    }
}
