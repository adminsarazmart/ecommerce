<?php

namespace Tests;

use App\Models\User;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\VendorWallet;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ?User $adminUser = null;
    protected ?User $vendorUser = null;
    protected ?User $customerUser = null;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh', ['--seed' => true]);
    }

    protected function signInAsAdmin(): User
    {
        if ($this->adminUser) {
            $this->actingAs($this->adminUser);
            return $this->adminUser;
        }

        $user = User::factory()->create([
            'email' => 'test-admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('Super Admin');

        $this->adminUser = $user;
        $this->actingAs($user);

        return $user;
    }

    protected function signInAsVendor(): User
    {
        if ($this->vendorUser) {
            $this->actingAs($this->vendorUser);
            return $this->vendorUser;
        }

        $user = User::factory()->create([
            'email' => 'test-vendor@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('Vendor');

        $vendor = Vendor::factory()->create([
            'user_id' => $user->id,
            'verification_status' => 'verified',
            'is_active' => true,
        ]);

        VendorWallet::create(['vendor_id' => $vendor->id, 'balance' => 10000]);

        $this->vendorUser = $user;
        $this->actingAs($user);

        return $user;
    }

    protected function signInAsCustomer(): User
    {
        if ($this->customerUser) {
            $this->actingAs($this->customerUser);
            return $this->customerUser;
        }

        $user = User::factory()->create([
            'email' => 'test-customer@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('Customer');

        Customer::factory()->create(['user_id' => $user->id]);

        $this->customerUser = $user;
        $this->actingAs($user);

        return $user;
    }

    protected function createModel(string $model, array $attributes = [])
    {
        $factoryClass = "Database\\Factories\\{$model}Factory";

        if (class_exists($factoryClass)) {
            return $factoryClass::new()->create($attributes);
        }

        $modelClass = "App\\Models\\{$model}";

        return $modelClass::factory()->create($attributes);
    }
}
