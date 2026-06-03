<?php

namespace App\Services;

use App\Repositories\ResellerRepository;
use App\Models\Reseller;
use App\Models\ResellerTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResellerService extends BaseService
{
    protected ResellerRepository $repository;

    public function __construct(ResellerRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($repository);
    }

    public function registerReseller(array $data): Reseller
    {
        DB::beginTransaction();
        try {
            $data['password'] = Hash::make($data['password']);
            $data['status'] = 'pending';
            $data['referral_code'] = $this->generateReferralCode();
            $reseller = $this->repository->create($data);
            DB::commit();
            return $reseller;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function calculateCommission(int $resellerId, float $amount, float $rate = 10): float
    {
        return $amount * ($rate / 100);
    }

    public function processWithdrawal(int $resellerId, float $amount): Reseller
    {
        DB::beginTransaction();
        try {
            $reseller = $this->repository->findOrFail($resellerId);
            $pendingCommissions = $reseller->transactions()
                ->where('type', 'commission')
                ->where('status', 'pending')
                ->sum('amount');

            if ($pendingCommissions < $amount) {
                throw new \Exception('Insufficient commission balance');
            }

            $reseller->transactions()->create([
                'type' => 'withdrawal',
                'amount' => -$amount,
                'status' => 'completed',
                'description' => 'Withdrawal processed',
            ]);

            DB::commit();
            return $reseller->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function generateReferralCode(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }
}
