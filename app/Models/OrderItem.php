<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id', 'product_id', 'variant_id', 'vendor_id', 'product_name',
        'product_sku', 'variant_name', 'quantity', 'unit_price', 'total_price',
        'cost_price', 'tax_amount', 'discount_amount', 'commission_rate',
        'commission_amount', 'vendor_earnings', 'is_refunded', 'refund_qty',
    ];

    protected function casts(): array
    {
        return ['is_refunded' => 'boolean'];
    }

    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
    public function vendor() { return $this->belongsTo(Vendor::class); }
}
