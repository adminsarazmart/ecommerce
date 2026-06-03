<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\VendorWallet;
use App\Models\VendorTransaction;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = Vendor::all();

        foreach ($vendors as $vendor) {
            if (!$vendor->wallet) {
                VendorWallet::create([
                    'vendor_id' => $vendor->id,
                    'balance' => fake()->randomFloat(2, 1000, 50000),
                ]);
            }

            if (fake()->boolean(60)) {
                VendorTransaction::create([
                    'vendor_id' => $vendor->id,
                    'type' => 'sale',
                    'amount' => fake()->randomFloat(2, 100, 5000),
                    'status' => 'completed',
                    'description' => 'Product sale commission',
                ]);
            }

            if (fake()->boolean(30)) {
                VendorTransaction::create([
                    'vendor_id' => $vendor->id,
                    'type' => 'withdrawal',
                    'amount' => fake()->randomFloat(2, -5000, -100),
                    'status' => 'completed',
                    'description' => 'Withdrawal processed',
                ]);
            }
        }
    }
}
