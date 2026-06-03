<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductVariant extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'product_id', 'name', 'sku', 'barcode', 'price', 'sale_price', 'cost_price',
        'stock', 'low_stock_threshold', 'weight', 'height', 'width', 'length',
        'is_default', 'is_active', 'sort_order', 'image', 'gallery',
    ];

    protected function casts(): array
    {
        return ['gallery' => 'json', 'is_default' => 'boolean', 'is_active' => 'boolean'];
    }

    public function product() { return $this->belongsTo(Product::class); }
    public function attributeValues() { return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values', 'variant_id', 'attribute_value_id'); }
    public function images() { return $this->hasMany(ProductImage::class, 'variant_id'); }
    public function stock() { return $this->hasMany(Stock::class, 'variant_id'); }
}
