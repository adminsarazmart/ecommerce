<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\VendorRating;
use App\Services\VendorService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    public function index()
    {
        $vendor = auth()->user()->vendor;

        $kpis = [
            'total_products' => Product::where('vendor_id', $vendor->id)->count(),
            'active_products' => Product::where('vendor_id', $vendor->id)->where('is_active', true)->count(),
            'total_orders' => Order::where('vendor_id', $vendor->id)->count(),
            'pending_orders' => Order::where('vendor_id', $vendor->id)->where('status', 'pending')->count(),
            'total_revenue' => Order::where('vendor_id', $vendor->id)->where('status', 'completed')->sum('grand_total'),
            'wallet_balance' => $vendor->wallet?->balance ?? 0,
            'average_rating' => VendorRating::where('vendor_id', $vendor->id)->avg('rating'),
            'total_ratings' => VendorRating::where('vendor_id', $vendor->id)->count(),
            'total_sales' => $vendor->total_sales,
        ];

        $recentOrders = Order::where('vendor_id', $vendor->id)
            ->with('customer.user')
            ->latest()
            ->take(10)
            ->get();

        $monthlySales = Order::where('vendor_id', $vendor->id)
            ->where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(grand_total) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        return Inertia::render('Vendor/Dashboard', [
            'kpis' => $kpis,
            'recentOrders' => $recentOrders,
            'monthlySales' => $monthlySales,
        ]);
    }
}
