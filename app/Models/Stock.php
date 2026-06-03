<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends BaseModel
{
    use HasFactory;

    protected $fillable = ['product_id', 'variant_id', 'warehouse_id', 'quantity', 'reserved_qty', 'low_stock_threshold'];

    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
    public function warehouse() { return $this->belongsTo(Warehouse::class); }
}
