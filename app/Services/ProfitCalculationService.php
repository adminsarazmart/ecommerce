<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfitCalculationService
{
    public function calculateOrderProfit(int $orderId): array
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        $totalRevenue = $order->total;
        $totalCost = 0;

        foreach ($order->items as $item) {
            $costPrice = $item->product?->cost_price ?? 0;
            $totalCost += $costPrice * $item->quantity;
        }

        $grossProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        return [
            'order_id' => $orderId,
            'order_number' => $order->order_number,
            'total_revenue' => (float) $totalRevenue,
            'total_cost' => (float) $totalCost,
            'gross_profit' => round($grossProfit, 2),
            'profit_margin' => round($profitMargin, 2),
        ];
    }

    public function calculatePeriodProfit(Carbon $start, Carbon $end): array
    {
        $orders = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->with('items.product');

        $totalRevenue = (clone $orders)->sum('total');
        $totalCost = 0;

        $orders->chunk(100, function ($chunk) use (&$totalCost) {
            foreach ($chunk as $order) {
                foreach ($order->items as $item) {
                    $costPrice = $item->product?->cost_price ?? 0;
                    $totalCost += $costPrice * $item->quantity;
                }
            }
        });

        $grossProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        return [
            'period_start' => $start->toDateTimeString(),
            'period_end' => $end->toDateTimeString(),
            'total_revenue' => round($totalRevenue, 2),
            'total_cost' => round($totalCost, 2),
            'gross_profit' => round($grossProfit, 2),
            'profit_margin' => round($profitMargin, 2),
            'order_count' => $orders->count(),
        ];
    }

    public function calculateNetProfit(Carbon $start, Carbon $end): array
    {
        $gross = $this->calculatePeriodProfit($start, $end);

        $expenses = DB::table('expenses')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $payrollCost = DB::table('payrolls')
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'paid')
            ->sum('net_amount');

        $totalExpenses = $expenses + $payrollCost;
        $netProfit = $gross['gross_profit'] - $totalExpenses;

        return array_merge($gross, [
            'total_expenses' => (float) $totalExpenses,
            'net_profit' => round($netProfit, 2),
            'net_margin' => $gross['total_revenue'] > 0
                ? round(($netProfit / $gross['total_revenue']) * 100, 2)
                : 0,
        ]);
    }

    public function distributeToShareholders(float $profitAmount, string $period): array
    {
        $shareholderService = app(ShareholderService::class);
        return $shareholderService->distributeProfits($profitAmount, $period)->toArray();
    }
}
