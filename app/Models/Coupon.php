<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'type', 'value', 'min_order_amount',
        'max_discount', 'usage_limit', 'usage_per_user', 'used_count',
        'start_date', 'end_date', 'is_active', 'applies_to', 'category_id',
        'product_id', 'vendor_id',
    ];

    protected function casts(): array
    {
        return ['start_date' => 'datetime', 'end_date' => 'datetime', 'is_active' => 'boolean'];
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function users() { return $this->belongsToMany(User::class, 'coupon_user')->withPivot(['used_at', 'order_id']); }
}
