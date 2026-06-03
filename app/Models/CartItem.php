<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['cart_id', 'product_id', 'variant_id', 'quantity', 'unit_price', 'total_price'];

    public function cart() { return $this->belongsTo(Cart::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
}
