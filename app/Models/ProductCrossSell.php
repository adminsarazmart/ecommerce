<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCrossSell extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['product_id', 'related_product_id', 'type'];

    public function product() { return $this->belongsTo(Product::class); }
    public function relatedProduct() { return $this->belongsTo(Product::class, 'related_product_id'); }
}
