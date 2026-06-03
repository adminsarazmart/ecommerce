<?php

namespace App\Jobs;

use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessVendorPayout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Vendor $vendor;
    public float $amount;

    public function __construct(Vendor $vendor, float $amount)
    {
        $this->vendor = $vendor;
        $this->amount = $amount;
    }

    public function handle(VendorService $vendorService): void
    {
        try {
            $vendorService->processPayout($this->vendor->id, $this->amount);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Vendor payout failed', [
                'vendor_id' => $this->vendor->id,
                'amount' => $this->amount,
                'error' => $e->getMessage(),
            ]);
            $this->fail($e);
        }
    }

    public function tags(): array
    {
        return ['payout', 'vendor', 'vendor:' . $this->vendor->id];
    }
}
