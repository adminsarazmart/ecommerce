<?php

namespace Database\Seeders;

use App\Models\MembershipLevel;
use Illuminate\Database\Seeder;

class MembershipLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'Regular', 'slug' => 'regular', 'min_spend' => 0, 'points_required' => 0, 'discount_rate' => 0, 'benefits' => json_encode(['free_shipping' => false, 'priority_support' => false])],
            ['name' => 'Silver', 'slug' => 'silver', 'min_spend' => 5000, 'points_required' => 1000, 'discount_rate' => 5, 'benefits' => json_encode(['free_shipping' => true, 'priority_support' => false])],
            ['name' => 'Gold', 'slug' => 'gold', 'min_spend' => 15000, 'points_required' => 3000, 'discount_rate' => 10, 'benefits' => json_encode(['free_shipping' => true, 'priority_support' => true])],
            ['name' => 'Platinum', 'slug' => 'platinum', 'min_spend' => 50000, 'points_required' => 10000, 'discount_rate' => 15, 'benefits' => json_encode(['free_shipping' => true, 'priority_support' => true, 'exclusive_access' => true])],
            ['name' => 'Diamond', 'slug' => 'diamond', 'min_spend' => 100000, 'points_required' => 25000, 'discount_rate' => 20, 'benefits' => json_encode(['free_shipping' => true, 'priority_support' => true, 'exclusive_access' => true, 'dedicated_manager' => true])],
        ];

        foreach ($levels as $level) {
            MembershipLevel::create($level);
        }
    }
}
