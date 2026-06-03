<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\VendorWallet;
use App\Models\UserProfile;
use App\Models\Reseller;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');
        UserProfile::create(['user_id' => $superAdmin->id, 'first_name' => 'Super', 'last_name' => 'Admin', 'phone' => '01700000000']);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        UserProfile::create(['user_id' => $admin->id, 'first_name' => 'Admin', 'last_name' => 'User', 'phone' => '01700000001']);

        $vendorUsers = [];
        $vendorData = [
            ['name' => 'TechStore BD', 'email' => 'vendor1@example.com', 'desc' => 'Leading electronics and gadgets store in Bangladesh'],
            ['name' => 'FashionHub', 'email' => 'vendor2@example.com', 'desc' => 'Premium fashion and lifestyle products'],
            ['name' => 'HomeMart', 'email' => 'vendor3@example.com', 'desc' => 'Your one-stop shop for home and garden needs'],
        ];

        foreach ($vendorData as $i => $vData) {
            $user = User::create([
                'name' => $vData['name'],
                'email' => $vData['email'],
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Vendor');
            UserProfile::create(['user_id' => $user->id, 'first_name' => explode(' ', $vData['name'])[0], 'last_name' => 'Vendor', 'phone' => '0170000000' . ($i + 2)]);

            $vendor = Vendor::create([
                'user_id' => $user->id,
                'store_name' => $vData['name'],
                'slug' => str($vData['name'])->slug() . '-' . uniqid(),
                'store_description' => $vData['desc'],
                'store_email' => $vData['email'],
                'store_phone' => '0170000000' . ($i + 2),
                'store_address' => 'Dhaka, Bangladesh',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'commission_rate' => 10,
                'commission_type' => 'percentage',
                'verification_status' => 'verified',
                'kyc_status' => 'approved',
                'is_active' => true,
                'total_products' => 0,
                'total_sales' => 0,
                'revenue' => 0,
                'join_date' => now(),
            ]);

            VendorWallet::create(['vendor_id' => $vendor->id, 'balance' => 0]);

            $vendorUsers[] = $user;
        }

        $resellerUser = User::create([
            'name' => 'Reseller Pro',
            'email' => 'reseller@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $resellerUser->assignRole('Reseller');
        UserProfile::create(['user_id' => $resellerUser->id, 'first_name' => 'Reseller', 'last_name' => 'Pro', 'phone' => '01700000005']);

        Reseller::create([
            'user_id' => $resellerUser->id,
            'referral_code' => 'REF' . strtoupper(uniqid()),
            'commission_rate' => 10,
            'total_earnings' => 0,
            'total_withdrawn' => 0,
            'current_balance' => 0,
            'status' => 'active',
            'verified_at' => now(),
        ]);

        $customerData = [
            ['name' => 'John Doe', 'email' => 'customer1@example.com'],
            ['name' => 'Jane Smith', 'email' => 'customer2@example.com'],
        ];

        foreach ($customerData as $cData) {
            $user = User::create([
                'name' => $cData['name'],
                'email' => $cData['email'],
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Customer');
            UserProfile::create(['user_id' => $user->id, 'first_name' => explode(' ', $cData['name'])[0], 'last_name' => explode(' ', $cData['name'])[1] ?? '']);

            Customer::create([
                'user_id' => $user->id,
                'total_orders' => 0,
                'total_spent' => 0,
                'loyalty_points' => 0,
                'wallet_balance' => 0,
                'membership_level' => 'regular',
            ]);
        }
    }
}
