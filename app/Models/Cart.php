<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends BaseModel
{
    use HasFactory;

    protected $fillable = ['customer_id', 'session_id', 'coupon_code', 'notes'];

    public function items() { return $this->hasMany(CartItem::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}
