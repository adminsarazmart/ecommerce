<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Stock;
use App\Services\ReportService;
use App\Services\ProfitCalculationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    protected ReportService $reportService;
    protected ProfitCalculationService $profitService;

    public function __construct(ReportService $reportService, ProfitCalculationService $profitService)
    {
        $this->reportService = $reportService;
        $this->profitService = $profitService;
    }

    public function sales(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->reportService->generateSalesReport($start, $end);

        $dailySales = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(grand_total) as total')
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Admin/Reports/Sales', [
            'report' => $report,
            'dailySales' => $dailySales,
            'startDate' => $start->format('Y-m-d'),
            'endDate' => $end->format('Y-m-d'),
        ]);
    }

    public function profit(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $profitData = $this->profitService->calculateNetProfit($start, $end);

        return Inertia::render('Admin/Reports/Profit', [
            'profitData' => $profitData,
            'startDate' => $start->format('Y-m-d'),
            'endDate' => $end->format('Y-m-d'),
        ]);
    }

    public function vendors(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $vendors = Vendor::with(['user', 'products'])
            ->withCount(['orders' => function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }])
            ->orderBy('total_sales', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Reports/Vendors', [
            'vendors' => $vendors,
            'startDate' => $start->format('Y-m-d'),
            'endDate' => $end->format('Y-m-d'),
        ]);
    }

    public function customers(Request $request)
    {
        $customers = Customer::with('user')
            ->withCount('orders')
            ->orderBy('total_spent', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Reports/Customers', [
            'customers' => $customers,
        ]);
    }

    public function inventory()
    {
        $report = $this->reportService->generateInventoryReport();

        $lowStock = Stock::with(['product', 'warehouse'])
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->orderBy('quantity')
            ->get();

        $topProducts = Product::with('category')
            ->orderBy('total_sales', 'desc')
            ->take(20)
            ->get();

        return Inertia::render('Admin/Reports/Inventory', [
            'report' => $report,
            'lowStock' => $lowStock,
            'topProducts' => $topProducts,
        ]);
    }

    public function pos()
    {
        $totalPosSales = \App\Models\PosOrder::where('status', 'completed')->sum('grand_total');
        $totalPosOrders = \App\Models\PosOrder::where('status', 'completed')->count();
        $todayPosSales = \App\Models\PosOrder::whereDate('created_at', today())->where('status', 'completed')->sum('grand_total');

        $salesByMethod = \App\Models\PosOrder::where('status', 'completed')
            ->selectRaw('payment_method, SUM(grand_total) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        return Inertia::render('Admin/Reports/POS', [
            'totalSales' => $totalPosSales,
            'totalOrders' => $totalPosOrders,
            'todaySales' => $todayPosSales,
            'salesByMethod' => $salesByMethod,
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sales,profit,inventory,customers,vendors',
            'format' => 'required|in:csv,pdf',
        ]);

        $parameters = $request->only(['start_date', 'end_date']);

        return $this->reportService->exportReport($request->type, $request->format, $parameters);
    }
}
