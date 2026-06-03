<?php

namespace App\Repositories;

use App\Models\Shareholder;
use App\Models\ShareholderLedger;
use App\Models\DividendPayout;
use Illuminate\Database\Eloquent\Collection;

class ShareholderRepository extends BaseRepository
{
    public function __construct(Shareholder $shareholder)
    {
        parent::__construct($shareholder);
    }

    public function getActive(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getLedger(int $shareholderId): Collection
    {
        return ShareholderLedger::where('shareholder_id', $shareholderId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getDividendHistory(int $shareholderId): Collection
    {
        return DividendPayout::where('shareholder_id', $shareholderId)
            ->orderBy('paid_at', 'desc')
            ->get();
    }
}
