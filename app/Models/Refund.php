<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'customer_id', 'items', 'amount', 'reason', 'status',
        'refunded_to', 'processed_by', 'notes',
    ];

    protected function casts(): array
    {
        return ['items' => 'json'];
    }

    public function order() { return $this->belongsTo(Order::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function processedBy() { return $this->belongsTo(User::class, 'processed_by'); }
}
