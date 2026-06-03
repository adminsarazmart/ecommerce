<?php

namespace App\Repositories;

use App\Models\Reseller;
use Illuminate\Database\Eloquent\Collection;

class ResellerRepository extends BaseRepository
{
    public function __construct(Reseller $reseller)
    {
        parent::__construct($reseller);
    }

    public function findByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function getCommissions(int $resellerId): Collection
    {
        return $this->model->findOrFail($resellerId)
            ->transactions()
            ->where('type', 'commission')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getEarnings(int $resellerId): float
    {
        return (float) $this->model->findOrFail($resellerId)
            ->transactions()
            ->where('type', 'commission')
            ->sum('amount');
    }
}
