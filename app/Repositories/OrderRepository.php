<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    public function findByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function findByCustomer(int $customerId): LengthAwarePaginator
    {
        return $this->model->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function findByVendor(int $vendorId): LengthAwarePaginator
    {
        return $this->model->whereHas('items', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getRevenueByPeriod(Carbon $start, Carbon $end): float
    {
        return (float) $this->model->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->sum('total');
    }

    public function getSalesByPeriod(Carbon $start, Carbon $end): int
    {
        return $this->model->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->count();
    }

    public function getTopSellingProducts(int $limit = 10): Collection
    {
        return $this->model->selectRaw('product_id, SUM(quantity) as total_qty')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->limit($limit)
            ->get();
    }
}
