<?php

namespace App\Modules\Payment\Models;

use App\Models\BaseModel;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends BaseModel
{
    protected $table = 'payment_gateway_transactions';

    protected $fillable = [
        'gateway',
        'payment_id',
        'order_id',
        'amount',
        'currency',
        'status',
        'response',
        'request_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'response' => 'array',
        'request_data' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
