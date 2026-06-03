<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function sales(Request $request)
    {
        $vendor = auth()->user()->vendor;
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $orders = Order::whereHas('items', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed');

        $totalSales = (clone $orders)->count();
        $totalRevenue = OrderItem::where('vendor_id', $vendor->id)
            ->whereHas('order', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])->where('status', 'completed');
            })
            ->sum('total_price');

        $dailySales = Order::whereHas('items', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Vendor/Reports/Sales', [
            'totalSales' => $totalSales,
            'totalRevenue' => round($totalRevenue, 2),
            'dailySales' => $dailySales,
            'startDate' => $start->format('Y-m-d'),
            'endDate' => $end->format('Y-m-d'),
        ]);
    }

    public function profit(Request $request)
    {
        $vendor = auth()->user()->vendor;
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $items = OrderItem::where('vendor_id', $vendor->id)
            ->whereHas('order', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])->where('status', 'completed');
            })
            ->get();

        $totalRevenue = $items->sum('total_price');
        $totalCost = $items->sum(fn ($item) => ($item->cost_price ?? 0) * $item->quantity);
        $grossProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        return Inertia::render('Vendor/Reports/Profit', [
            'totalRevenue' => round($totalRevenue, 2),
            'totalCost' => round($totalCost, 2),
            'grossProfit' => round($grossProfit, 2),
            'profitMargin' => round($profitMargin, 2),
            'startDate' => $start->format('Y-m-d'),
            'endDate' => $end->format('Y-m-d'),
        ]);
    }

    public function inventory()
    {
        $vendor = auth()->user()->vendor;

        $products = Product::where('vendor_id', $vendor->id)
            ->with(['stock.warehouse', 'category'])
            ->paginate(15);

        return Inertia::render('Vendor/Reports/Inventory', [
            'products' => $products,
        ]);
    }
}
