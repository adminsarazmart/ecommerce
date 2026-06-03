<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['customer_id', 'product_id', 'variant_id'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
}
