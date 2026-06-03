<?php

namespace App\Jobs;

use App\Services\ProfitCalculationService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDividendDistribution implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $period;
    public string $startDate;
    public string $endDate;

    public function __construct(string $period, string $startDate, string $endDate)
    {
        $this->period = $period;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function handle(ProfitCalculationService $profitService): void
    {
        try {
            $profitData = $profitService->calculateNetProfit(
                Carbon::parse($this->startDate),
                Carbon::parse($this->endDate)
            );

            if ($profitData['net_profit'] > 0) {
                $profitService->distributeToShareholders(
                    $profitData['net_profit'] * 0.5,
                    $this->period
                );
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Dividend distribution failed', [
                'period' => $this->period,
                'error' => $e->getMessage(),
            ]);
            $this->fail($e);
        }
    }

    public function tags(): array
    {
        return ['dividend', 'distribution', 'period:' . $this->period];
    }
}
