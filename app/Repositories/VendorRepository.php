<?php

namespace App\Repositories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Collection;

class VendorRepository extends BaseRepository
{
    public function __construct(Vendor $vendor)
    {
        parent::__construct($vendor);
    }

    public function findByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function findByVerification(string $status): Collection
    {
        return $this->model->where('verification_status', $status)->get();
    }

    public function getTopVendors(int $limit = 10): Collection
    {
        return $this->model->where('status', 'active')
            ->orderBy('total_sales', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getVendorAnalytics(int $vendorId, string $startDate, string $endDate): array
    {
        $vendor = $this->findOrFail($vendorId);
        $orders = $vendor->orders()->whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->where('status', 'completed')->sum('total'),
            'total_products' => $vendor->products()->count(),
            'average_rating' => $vendor->ratings()->avg('rating'),
        ];
    }
}
