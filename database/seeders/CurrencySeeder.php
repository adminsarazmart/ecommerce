<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'exchange_rate' => 1.00, 'is_default' => true, 'is_active' => true],
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'exchange_rate' => 0.92, 'is_default' => false, 'is_active' => true],
            ['name' => 'Bangladeshi Taka', 'code' => 'BDT', 'symbol' => '৳', 'exchange_rate' => 110.00, 'is_default' => false, 'is_active' => true],
            ['name' => 'Indian Rupee', 'code' => 'INR', 'symbol' => '₹', 'exchange_rate' => 83.00, 'is_default' => false, 'is_active' => true],
            ['name' => 'British Pound', 'code' => 'GBP', 'symbol' => '£', 'exchange_rate' => 0.79, 'is_default' => false, 'is_active' => true],
            ['name' => 'Canadian Dollar', 'code' => 'CAD', 'symbol' => 'CA$', 'exchange_rate' => 1.36, 'is_default' => false, 'is_active' => true],
            ['name' => 'Australian Dollar', 'code' => 'AUD', 'symbol' => 'A$', 'exchange_rate' => 1.54, 'is_default' => false, 'is_active' => true],
            ['name' => 'Japanese Yen', 'code' => 'JPY', 'symbol' => '¥', 'exchange_rate' => 150.00, 'is_default' => false, 'is_active' => true],
            ['name' => 'Chinese Yuan', 'code' => 'CNY', 'symbol' => '¥', 'exchange_rate' => 7.24, 'is_default' => false, 'is_active' => true],
            ['name' => 'UAE Dirham', 'code' => 'AED', 'symbol' => 'د.إ', 'exchange_rate' => 3.67, 'is_default' => false, 'is_active' => true],
            ['name' => 'Saudi Riyal', 'code' => 'SAR', 'symbol' => '﷼', 'exchange_rate' => 3.75, 'is_default' => false, 'is_active' => true],
            ['name' => 'Malaysian Ringgit', 'code' => 'MYR', 'symbol' => 'RM', 'exchange_rate' => 4.72, 'is_default' => false, 'is_active' => true],
            ['name' => 'Singapore Dollar', 'code' => 'SGD', 'symbol' => 'S$', 'exchange_rate' => 1.34, 'is_default' => false, 'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
