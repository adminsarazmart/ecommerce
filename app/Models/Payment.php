<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends BaseModel
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'payment_method',
        'status',
        'gateway_response',
        'refunded_amount',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'refunded_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
