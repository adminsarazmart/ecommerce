<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerWalletTransaction extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'customer_id', 'amount', 'type', 'reference_type', 'reference_id',
        'description', 'balance_before', 'balance_after',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
}
