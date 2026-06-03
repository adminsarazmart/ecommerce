<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    public function getRevenueAnalytics(Carbon $start, Carbon $end): array
    {
        $orders = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed');

        $dailyRevenue = (clone $orders)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = (clone $orders)->sum('total');
        $previousPeriod = (clone $orders)
            ->whereBetween('created_at', [Carbon::parse($start)->subDays($start->diffInDays($end) + 1), $start])
            ->sum('total');
        $growthRate = $previousPeriod > 0 ? (($totalRevenue - $previousPeriod) / $previousPeriod) * 100 : 0;

        return [
            'total_revenue' => (float) $totalRevenue,
            'daily_revenue' => $dailyRevenue,
            'average_order_value' => (float) (clone $orders)->avg('total'),
            'growth_rate' => round($growthRate, 2),
            'period_start' => $start->toDateTimeString(),
            'period_end' => $end->toDateTimeString(),
        ];
    }

    public function getProductAnalytics(Carbon $start, Carbon $end): array
    {
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.total_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();

        return [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'top_selling' => $topProducts,
            'average_rating' => Product::avg('average_rating'),
        ];
    }

    public function getCustomerAnalytics(Carbon $start, Carbon $end): array
    {
        $newCustomers = Customer::whereBetween('created_at', [$start, $end])->count();
        $activeCustomers = Customer::where('is_active', true)->count();
        $totalCustomers = Customer::count();

        $repeatCustomers = DB::table('orders')
            ->select('customer_id', DB::raw('COUNT(*) as order_count'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('customer_id')
            ->having('order_count', '>', 1)
            ->count();

        return [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'new_customers' => $newCustomers,
            'repeat_customers' => $repeatCustomers,
            'customer_retention_rate' => $totalCustomers > 0
                ? round(($repeatCustomers / $totalCustomers) * 100, 2)
                : 0,
        ];
    }

    public function getVendorAnalytics(Carbon $start, Carbon $end): array
    {
        $totalVendors = Vendor::count();
        $activeVendors = Vendor::where('status', 'active')->count();
        $pendingVendors = Vendor::where('status', 'pending')->count();

        $vendorRevenue = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->whereNotNull('order_items.vendor_id')
            ->select('order_items.vendor_id', DB::raw('SUM(order_items.total_price) as revenue'))
            ->groupBy('order_items.vendor_id')
            ->get();

        return [
            'total_vendors' => $totalVendors,
            'active_vendors' => $activeVendors,
            'pending_vendors' => $pendingVendors,
            'vendor_revenue' => $vendorRevenue,
            'average_revenue_per_vendor' => $activeVendors > 0
                ? round($vendorRevenue->avg('revenue'), 2)
                : 0,
        ];
    }

    public function getTrafficAnalytics(Carbon $start, Carbon $end): array
    {
        return [
            'total_visits' => 0,
            'unique_visitors' => 0,
            'page_views' => 0,
            'bounce_rate' => 0,
            'average_session_duration' => 0,
            'top_pages' => [],
            'traffic_sources' => [],
        ];
    }
}
