<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResellerTransaction extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'reseller_id', 'amount', 'type', 'description', 'reference_type',
        'reference_id', 'balance_before', 'balance_after', 'status',
    ];

    public function reseller() { return $this->belongsTo(Reseller::class); }
}
