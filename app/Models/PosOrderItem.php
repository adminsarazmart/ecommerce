<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosOrderItem extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['pos_order_id', 'product_id', 'variant_id', 'quantity', 'unit_price', 'total_price'];

    public function posOrder() { return $this->belongsTo(PosOrder::class, 'pos_order_id'); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
}
