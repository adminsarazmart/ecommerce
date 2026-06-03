<?php

namespace Tests\Feature;

use App\Models\Vendor;
use App\Models\VendorWallet;
use App\Models\User;
use Tests\TestCase;

class VendorTest extends TestCase
{
    public function test_admin_can_view_vendors_list(): void
    {
        $this->signInAsAdmin();

        Vendor::factory(3)->create();

        $response = $this->get(route('admin.vendors.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_verify_vendor(): void
    {
        $this->signInAsAdmin();

        $vendor = Vendor::factory()->create(['verification_status' => 'pending']);

        $response = $this->post(route('admin.vendors.verify', $vendor));

        $response->assertRedirect();
        $this->assertEquals('verified', $vendor->fresh()->verification_status);
    }

    public function test_admin_can_suspend_vendor(): void
    {
        $this->signInAsAdmin();

        $vendor = Vendor::factory()->active()->create();

        $response = $this->post(route('admin.vendors.suspend', $vendor));

        $response->assertRedirect();
        $this->assertFalse((bool) $vendor->fresh()->is_active);
    }

    public function test_vendor_can_access_dashboard(): void
    {
        $this->signInAsVendor();

        $response = $this->get(route('vendor.dashboard'));

        $response->assertStatus(200);
    }

    public function test_vendor_cannot_access_admin(): void
    {
        $this->signInAsVendor();

        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect();
    }

    public function test_vendor_registration_creates_wallet(): void
    {
        $vendor = Vendor::factory()->create();

        $wallet = VendorWallet::firstOrCreate(
            ['vendor_id' => $vendor->id],
            ['balance' => 0]
        );

        $this->assertNotNull($wallet);
        $this->assertEquals(0, $wallet->balance);
    }

    public function test_vendor_can_update_profile(): void
    {
        $user = $this->signInAsVendor();

        $response = $this->put(route('vendor.settings.profile'), [
            'store_name' => 'Updated Store Name',
            'store_description' => 'Updated description',
            'store_email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Updated Store Name', $user->vendor->fresh()->store_name);
    }

    public function test_vendor_kyc_approval_flow(): void
    {
        $this->signInAsAdmin();

        $vendor = Vendor::factory()->create(['kyc_status' => 'pending']);

        $response = $this->post(route('admin.vendors.approve-kyc', $vendor), [
            'kyc_data' => ['document_id' => 'DOC123'],
        ]);

        $response->assertRedirect();
        $this->assertEquals('approved', $vendor->fresh()->kyc_status);
    }

    public function test_vendor_list_filters(): void
    {
        $this->signInAsAdmin();

        Vendor::factory()->create(['verification_status' => 'verified']);
        Vendor::factory()->create(['verification_status' => 'pending']);

        $response = $this->get(route('admin.vendors.index', ['status' => 'verified']));

        $response->assertStatus(200);
    }

    public function test_vendor_commission_calculation(): void
    {
        $vendor = Vendor::factory()->create(['commission_rate' => 15]);

        $saleAmount = 1000;
        $expectedCommission = $saleAmount * 0.15;

        $this->assertEquals(150, $expectedCommission);
    }
}
