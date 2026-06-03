<?php

namespace App\Jobs;

use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateProductReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $type;
    public array $parameters;

    public function __construct(string $type, array $parameters = [])
    {
        $this->type = $type;
        $this->parameters = $parameters;
    }

    public function handle(ReportService $reportService): void
    {
        try {
            $start = Carbon::parse($this->parameters['start'] ?? now()->startOfMonth());
            $end = Carbon::parse($this->parameters['end'] ?? now()->endOfMonth());

            match ($this->type) {
                'sales' => $reportService->generateSalesReport($start, $end),
                'profit' => $reportService->generateProfitReport($start, $end),
                'inventory' => $reportService->generateInventoryReport(),
                default => throw new \InvalidArgumentException("Unknown report type: {$this->type}"),
            };
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Report generation failed', [
                'type' => $this->type,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function tags(): array
    {
        return ['report', $this->type];
    }
}
