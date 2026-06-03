<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransferItem extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['transfer_id', 'product_id', 'variant_id', 'quantity'];

    public function transfer() { return $this->belongsTo(WarehouseTransfer::class, 'transfer_id'); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
}
