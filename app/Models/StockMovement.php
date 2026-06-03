<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_id', 'variant_id', 'warehouse_id', 'from_warehouse_id',
        'reference_type', 'reference_id', 'quantity', 'type', 'before_qty',
        'after_qty', 'description', 'created_by',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function fromWarehouse() { return $this->belongsTo(Warehouse::class, 'from_warehouse_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
