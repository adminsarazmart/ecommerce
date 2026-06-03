<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShareholderLedger extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'shareholder_ledgers';

    protected $fillable = [
        'shareholder_id', 'transaction_type', 'amount', 'reference_type',
        'reference_id', 'description', 'balance_before', 'balance_after',
        'transaction_date',
    ];

    protected function casts(): array
    {
        return ['transaction_date' => 'datetime'];
    }

    public function shareholder() { return $this->belongsTo(Shareholder::class); }
}
