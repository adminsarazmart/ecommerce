<?php

namespace App\Services;

use App\Repositories\VendorRepository;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VendorService extends BaseService
{
    protected VendorRepository $repository;

    public function __construct(VendorRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($repository);
    }

    public function registerVendor(array $data): Vendor
    {
        DB::beginTransaction();
        try {
            $data['status'] = 'pending';
            $data['verification_status'] = 'unverified';
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            $vendor = $this->repository->create($data);
            $vendor->wallet()->create(['balance' => 0]);
            DB::commit();
            return $vendor->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function verifyVendor(int $vendorId): Vendor
    {
        $vendor = $this->repository->findOrFail($vendorId);
        $vendor->update(['verification_status' => 'verified']);
        return $vendor->fresh();
    }

    public function approveKyc(int $vendorId, array $kycData): Vendor
    {
        $vendor = $this->repository->findOrFail($vendorId);
        $vendor->update([
            'verification_status' => 'verified',
            'kyc_approved_at' => now(),
            'kyc_data' => $kycData,
        ]);
        return $vendor->fresh();
    }

    public function calculateCommission(int $vendorId, float $amount, ?string $type = 'percentage'): float
    {
        $vendor = $this->repository->findOrFail($vendorId);
        $rate = $vendor->commission_rate ?? 10;
        return $type === 'percentage' ? ($amount * $rate / 100) : $rate;
    }

    public function processPayout(int $vendorId, float $amount): Vendor
    {
        DB::beginTransaction();
        try {
            $vendor = $this->repository->findOrFail($vendorId);
            $wallet = $vendor->wallet;
            if ($wallet->balance < $amount) {
                throw new \Exception('Insufficient balance');
            }
            $wallet->decrement('balance', $amount);
            $vendor->payouts()->create([
                'amount' => $amount,
                'status' => 'completed',
                'processed_at' => now(),
            ]);
            DB::commit();
            return $vendor->fresh(['wallet', 'payouts']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getVendorDashboard(int $vendorId): array
    {
        $vendor = $this->repository->findOrFail($vendorId);
        $analytics = $this->repository->getVendorAnalytics($vendorId, now()->startOfMonth(), now()->endOfMonth());

        return array_merge([
            'vendor' => $vendor,
            'wallet_balance' => $vendor->wallet?->balance ?? 0,
            'products_count' => $vendor->products()->count(),
            'active_products' => $vendor->products()->where('is_active', true)->count(),
            'pending_orders' => $vendor->orders()->where('status', 'pending')->count(),
            'rating' => $vendor->ratings()->avg('rating'),
        ], $analytics);
    }
}
