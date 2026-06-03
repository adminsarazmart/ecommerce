<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $customer)
    {
        parent::__construct($customer);
    }

    public function findActive(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getTopCustomers(int $limit = 10): Collection
    {
        return $this->model->where('is_active', true)
            ->orderBy('total_orders', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getCustomerAnalytics(int $customerId): array
    {
        $customer = $this->findOrFail($customerId);

        return [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->where('status', 'completed')->sum('total'),
            'total_reviews' => $customer->reviews()->count(),
            'wishlist_count' => $customer->wishlist()->count(),
            'loyalty_points' => $customer->loyaltyPoints()->sum('points'),
            'membership_level' => $customer->membershipLevel?->name,
            'last_order_date' => $customer->orders()->latest()->first()?->created_at,
        ];
    }
}
