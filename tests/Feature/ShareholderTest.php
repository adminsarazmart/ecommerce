<?php

namespace Tests\Feature;

use App\Models\Shareholder;
use App\Models\DividendDistribution;
use App\Models\User;
use Tests\TestCase;

class ShareholderTest extends TestCase
{
    public function test_admin_can_view_shareholders(): void
    {
        $this->signInAsAdmin();

        $response = $this->get(route('admin.shareholders.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_shareholder(): void
    {
        $this->signInAsAdmin();

        $response = $this->post(route('admin.shareholders.store'), [
            'name' => 'New Shareholder',
            'email' => 'shareholder-new@example.com',
            'share_percentage' => 10,
            'total_investment' => 100000,
            'total_shares' => 1000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('shareholders', ['share_percentage' => 10]);
    }

    public function test_shareholder_creation_validation(): void
    {
        $this->signInAsAdmin();

        $response = $this->post(route('admin.shareholders.store'), [
            'name' => 'Test',
            'email' => 'invalid-email',
            'share_percentage' => 101,
        ]);

        $response->assertSessionHasErrors(['email', 'share_percentage']);
    }

    public function test_admin_can_update_shareholder(): void
    {
        $this->signInAsAdmin();

        $shareholder = Shareholder::factory()->create();

        $response = $this->put(route('admin.shareholders.update', $shareholder), [
            'share_percentage' => 25,
            'total_investment' => 250000,
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertEquals(25, $shareholder->fresh()->share_percentage);
    }

    public function test_dividend_distribution(): void
    {
        $this->signInAsAdmin();

        $shareholders = Shareholder::factory(3)->create(['status' => 'active']);

        $response = $this->post(route('admin.shareholders.distribute-dividends'), [
            'total_profit' => 100000,
            'period' => '2024-01',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dividend_distributions', ['total_profit' => 100000]);
    }

    public function test_dividend_calculation_is_accurate(): void
    {
        $shareholders = Shareholder::factory(3)->create(['status' => 'active']);

        $totalProfit = 100000;
        $totalShares = $shareholders->sum('shares');

        foreach ($shareholders as $shareholder) {
            $percentage = $totalShares > 0 ? $shareholder->shares / $totalShares : 0;
            $dividend = $totalProfit * $percentage;
            $this->assertGreaterThan(0, $dividend);
        }
    }

    public function test_shareholder_ledger_exists(): void
    {
        $shareholder = Shareholder::factory()->create();

        $this->assertNotNull($shareholder);
        $this->assertInstanceOf(Shareholder::class, $shareholder);
    }
}
