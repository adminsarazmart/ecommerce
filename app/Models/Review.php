<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'variant_id', 'customer_id', 'vendor_id', 'rating',
        'title', 'body', 'images', 'is_approved', 'is_featured', 'helpful_count',
    ];

    protected function casts(): array
    {
        return ['images' => 'json', 'is_approved' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }
}
