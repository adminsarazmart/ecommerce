<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerService extends BaseService
{
    protected CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($repository);
    }

    public function registerCustomer(array $data): Customer
    {
        DB::beginTransaction();
        try {
            $data['password'] = Hash::make($data['password']);
            $data['is_active'] = true;
            $customer = $this->repository->create($data);
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProfile(int $id, array $data): Customer
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->repository->update($id, $data);
    }

    public function getDashboard(int $customerId): array
    {
        $customer = $this->repository->findOrFail($customerId);
        return [
            'customer' => $customer,
            'recent_orders' => $customer->orders()->latest()->take(5)->get(),
            'wishlist_count' => $customer->wishlist()->count(),
            'cart_count' => $customer->cart?->items()->count() ?? 0,
            'loyalty_points' => $customer->loyaltyPoints()->sum('points'),
            'reviews_count' => $customer->reviews()->count(),
        ];
    }

    public function getOrderHistory(int $customerId, int $perPage = 15)
    {
        return $this->repository->findOrFail($customerId)
            ->orders()
            ->with('items.product')
            ->latest()
            ->paginate($perPage);
    }
}
