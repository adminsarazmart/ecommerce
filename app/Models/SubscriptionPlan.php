<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'billing_cycle', 'features',
        'product_limit', 'storage_limit', 'bandwidth_limit', 'is_active', 'is_featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['features' => 'json', 'is_active' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function vendorSubscriptions() { return $this->hasMany(VendorSubscription::class, 'plan_id'); }
}
