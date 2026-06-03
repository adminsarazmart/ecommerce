<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosOrder extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'session_id', 'order_id', 'customer_id', 'register_id', 'pos_number',
        'type', 'payment_method', 'subtotal', 'tax', 'discount', 'grand_total',
        'paid_amount', 'change_amount', 'status', 'created_by',
    ];

    public function session() { return $this->belongsTo(PosSession::class, 'session_id'); }
    public function order() { return $this->belongsTo(Order::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function items() { return $this->hasMany(PosOrderItem::class, 'pos_order_id'); }
}
