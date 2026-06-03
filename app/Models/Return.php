<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnItem extends BaseModel
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'order_id', 'customer_id', 'items', 'reason', 'status',
        'return_type', 'exchange_product_id',
    ];

    protected function casts(): array
    {
        return ['items' => 'json'];
    }

    public function order() { return $this->belongsTo(Order::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function exchangeProduct() { return $this->belongsTo(Product::class, 'exchange_product_id'); }
}
