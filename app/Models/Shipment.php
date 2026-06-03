<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id', 'tracking_number', 'carrier', 'status', 'shipped_at',
        'estimated_delivery', 'delivered_at', 'address',
    ];

    protected function casts(): array
    {
        return [
            'address' => 'json', 'shipped_at' => 'datetime',
            'estimated_delivery' => 'datetime', 'delivered_at' => 'datetime',
        ];
    }

    public function order() { return $this->belongsTo(Order::class); }
}
