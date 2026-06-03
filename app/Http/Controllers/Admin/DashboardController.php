<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Vendor;
use App\Services\AnalyticsService;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $revenueAnalytics = $this->analyticsService->getRevenueAnalytics($start, $end);
        $customerAnalytics = $this->analyticsService->getCustomerAnalytics($start, $end);
        $productAnalytics = $this->analyticsService->getProductAnalytics($start, $end);
        $vendorAnalytics = $this->analyticsService->getVendorAnalytics($start, $end);

        $recentOrders = Order::with('customer.user')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('grand_total'),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'total_vendors' => Vendor::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->where('status', 'completed')->sum('grand_total'),
        ];

        $chartData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(grand_total) as revenue')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'revenueAnalytics' => $revenueAnalytics,
            'customerAnalytics' => $customerAnalytics,
            'productAnalytics' => $productAnalytics,
            'vendorAnalytics' => $vendorAnalytics,
            'recentOrders' => $recentOrders,
            'chartData' => $chartData,
        ]);
    }
}
