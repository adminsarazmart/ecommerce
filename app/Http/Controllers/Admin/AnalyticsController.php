<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalyticsController extends Controller
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

        $revenue = $this->analyticsService->getRevenueAnalytics($start, $end);
        $products = $this->analyticsService->getProductAnalytics($start, $end);
        $customers = $this->analyticsService->getCustomerAnalytics($start, $end);
        $vendors = $this->analyticsService->getVendorAnalytics($start, $end);
        $traffic = $this->analyticsService->getTrafficAnalytics($start, $end);

        return Inertia::render('Admin/Analytics/Index', [
            'revenue' => $revenue,
            'products' => $products,
            'customers' => $customers,
            'vendors' => $vendors,
            'traffic' => $traffic,
        ]);
    }

    public function revenue(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfYear();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfYear();

        $analytics = $this->analyticsService->getRevenueAnalytics($start, $end);

        return Inertia::render('Admin/Analytics/Revenue', [
            'analytics' => $analytics,
            'startDate' => $start->format('Y-m-d'),
            'endDate' => $end->format('Y-m-d'),
        ]);
    }

    public function products(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $analytics = $this->analyticsService->getProductAnalytics($start, $end);

        return Inertia::render('Admin/Analytics/Products', [
            'analytics' => $analytics,
            'startDate' => $start->format('Y-m-d'),
            'endDate' => $end->format('Y-m-d'),
        ]);
    }

    public function customers(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $analytics = $this->analyticsService->getCustomerAnalytics($start, $end);

        return Inertia::render('Admin/Analytics/Customers', [
            'analytics' => $analytics,
        ]);
    }

    public function vendors(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $analytics = $this->analyticsService->getVendorAnalytics($start, $end);

        return Inertia::render('Admin/Analytics/Vendors', [
            'analytics' => $analytics,
        ]);
    }

    public function traffic(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $analytics = $this->analyticsService->getTrafficAnalytics($start, $end);

        return Inertia::render('Admin/Analytics/Traffic', [
            'analytics' => $analytics,
        ]);
    }
}
