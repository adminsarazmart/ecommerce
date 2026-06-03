<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends BaseModel implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'vendor_id', 'name', 'slug', 'sku', 'description', 'short_description',
        'brand_id', 'category_id', 'unit_price', 'sale_price', 'cost_price',
        'wholesale_price', 'min_wholesale_qty', 'tax', 'tax_type', 'weight',
        'height', 'width', 'length', 'meta_title', 'meta_description', 'tags',
        'is_active', 'is_featured', 'is_trending', 'is_new', 'allow_backorder',
        'min_qty', 'max_qty', 'seo_score', 'total_ratings', 'total_reviews',
        'total_sales', 'total_wishlist', 'is_virtual', 'download_url',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'json', 'is_active' => 'boolean', 'is_featured' => 'boolean',
            'is_trending' => 'boolean', 'is_new' => 'boolean', 'allow_backorder' => 'boolean',
            'is_virtual' => 'boolean', 'deleted_at' => 'datetime',
        ];
    }

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function variants() { return $this->hasMany(ProductVariant::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function questions() { return $this->hasMany(QuestionAnswer::class); }
    public function crossSells() { return $this->hasMany(ProductCrossSell::class); }
    public function stock() { return $this->hasMany(Stock::class); }
    public function translations() { return $this->hasMany(ProductTranslation::class); }
    public function tags() { return $this->belongsToMany(Tag::class, 'product_tag'); }
}
