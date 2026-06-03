<?php

namespace App\Modules\Payment\Models;

use App\Models\BaseModel;

class PaymentLog extends BaseModel
{
    protected $table = 'payment_logs';

    protected $fillable = [
        'gateway',
        'type',
        'endpoint',
        'request_data',
        'response',
        'payment_id',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response' => 'array',
    ];
}
