<?php

namespace App\Jobs;

use App\Models\Vendor;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateVendorReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Vendor $vendor;
    public string $startDate;
    public string $endDate;

    public function __construct(Vendor $vendor, string $startDate, string $endDate)
    {
        $this->vendor = $vendor;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function handle(ReportService $reportService): void
    {
        try {
            $reportService->generateVendorReport(
                $this->vendor->id,
                Carbon::parse($this->startDate),
                Carbon::parse($this->endDate)
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Vendor report generation failed', [
                'vendor_id' => $this->vendor->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function tags(): array
    {
        return ['report', 'vendor', 'vendor:' . $this->vendor->id];
    }
}
