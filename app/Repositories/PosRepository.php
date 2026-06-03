<?php

namespace App\Repositories;

use App\Models\PosSession;
use App\Models\PosOrder;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class PosRepository extends BaseRepository
{
    public function __construct(PosSession $posSession)
    {
        parent::__construct($posSession);
    }

    public function getSessionOrders(int $sessionId): Collection
    {
        return PosOrder::where('pos_session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getDailySales(string $date): float
    {
        return (float) PosOrder::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->sum('total');
    }

    public function getPaymentMethods(string $startDate, string $endDate): Collection
    {
        return PosOrder::selectRaw('payment_method, SUM(total) as total, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();
    }
}
