<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashbackTransaction extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['customer_id', 'order_id', 'amount', 'rate', 'status'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
}
