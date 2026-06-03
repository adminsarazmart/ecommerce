<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;

class ReportRepository
{
    public function generateSalesReport(Carbon $start, Carbon $end): array
    {
        $orders = Order::whereBetween('created_at', [$start, $end]);
        $completed = (clone $orders)->where('status', 'completed');

        return [
            'total_orders' => $orders->count(),
            'completed_orders' => $completed->count(),
            'total_revenue' => (float) $completed->sum('total'),
            'average_order_value' => (float) $completed->avg('total'),
            'total_items_sold' => (int) $completed->join('order_items', 'orders.id', '=', 'order_items.order_id')->sum('order_items.quantity'),
            'period_start' => $start->toDateTimeString(),
            'period_end' => $end->toDateTimeString(),
        ];
    }

    public function generateProfitReport(Carbon $start, Carbon $end): array
    {
        $completed = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed');

        $totalRevenue = (float) (clone $completed)->sum('total');
        $totalCost = (float) (clone $completed)->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(\DB::raw('order_items.quantity * products.cost_price'));

        return [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'gross_profit' => $totalRevenue - $totalCost,
            'profit_margin' => $totalRevenue > 0 ? round((($totalRevenue - $totalCost) / $totalRevenue) * 100, 2) : 0,
            'period_start' => $start->toDateTimeString(),
            'period_end' => $end->toDateTimeString(),
        ];
    }

    public function generateInventoryReport(): array
    {
        $products = Product::select('id', 'name', 'sku', 'stock_quantity', 'low_stock_threshold', 'is_active')
            ->with(['stocks' => function ($query) {
                $query->with('warehouse');
            }])
            ->get();

        $totalStock = $products->sum('stock_quantity');
        $lowStock = $products->filter(function ($product) {
            return $product->stock_quantity <= ($product->low_stock_threshold ?? 10);
        });
        $outOfStock = $products->filter(function ($product) {
            return $product->stock_quantity <= 0;
        });

        return [
            'total_products' => $products->count(),
            'total_stock_quantity' => $totalStock,
            'low_stock_count' => $lowStock->count(),
            'out_of_stock_count' => $outOfStock->count(),
            'low_stock_products' => $lowStock->values(),
            'out_of_stock_products' => $outOfStock->values(),
        ];
    }
}
