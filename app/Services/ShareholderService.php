<?php

namespace App\Services;

use App\Repositories\ShareholderRepository;
use App\Models\Shareholder;
use App\Models\ShareholderLedger;
use App\Models\DividendDistribution;
use App\Models\DividendPayout;
use Illuminate\Support\Facades\DB;

class ShareholderService extends BaseService
{
    protected ShareholderRepository $repository;

    public function __construct(ShareholderRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($repository);
    }

    public function calculateDividends(float $totalProfit): array
    {
        $shareholders = $this->repository->getActive();
        $totalShares = $shareholders->sum('shares');
        $distributions = [];

        foreach ($shareholders as $shareholder) {
            $percentage = $totalShares > 0 ? $shareholder->shares / $totalShares : 0;
            $dividend = $totalProfit * $percentage;
            $distributions[] = [
                'shareholder_id' => $shareholder->id,
                'amount' => round($dividend, 2),
                'percentage' => round($percentage * 100, 4),
            ];
        }

        return $distributions;
    }

    public function distributeProfits(float $totalProfit, string $period): DividendDistribution
    {
        DB::beginTransaction();
        try {
            $distribution = DividendDistribution::create([
                'total_profit' => $totalProfit,
                'period' => $period,
                'distributed_at' => now(),
                'status' => 'completed',
            ]);

            $shareholders = $this->repository->getActive();
            $totalShares = $shareholders->sum('shares');

            foreach ($shareholders as $shareholder) {
                $percentage = $totalShares > 0 ? $shareholder->shares / $totalShares : 0;
                $amount = round($totalProfit * $percentage, 2);

                DividendPayout::create([
                    'dividend_distribution_id' => $distribution->id,
                    'shareholder_id' => $shareholder->id,
                    'amount' => $amount,
                    'percentage' => round($percentage * 100, 4),
                    'paid_at' => now(),
                ]);

                ShareholderLedger::create([
                    'shareholder_id' => $shareholder->id,
                    'type' => 'dividend',
                    'amount' => $amount,
                    'description' => "Dividend distribution for {$period}",
                ]);
            }

            DB::commit();
            return $distribution->load('payouts.shareholder');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getLedger(int $shareholderId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->getLedger($shareholderId);
    }

    public function generateReports(string $period): array
    {
        $totalDistributed = DividendDistribution::where('period', $period)->sum('total_profit');
        $shareholders = $this->repository->getActive();

        return [
            'period' => $period,
            'total_distributed' => $totalDistributed,
            'shareholder_count' => $shareholders->count(),
            'shareholders' => $shareholders->map(function ($s) {
                return [
                    'name' => $s->name,
                    'shares' => $s->shares,
                    'total_dividends' => $s->payouts()->sum('amount'),
                ];
            }),
        ];
    }
}
