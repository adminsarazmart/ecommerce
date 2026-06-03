<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'purchase_order_id', 'product_id', 'variant_id', 'quantity',
        'unit_price', 'total_price', 'received_qty', 'damaged_qty',
    ];

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id'); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
}
