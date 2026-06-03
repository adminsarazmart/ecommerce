<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'store_name', 'slug', 'store_description', 'store_logo',
        'store_banner', 'store_email', 'store_phone', 'store_address', 'city',
        'state', 'zip', 'country', 'commission_rate', 'commission_type',
        'verification_status', 'kyc_status', 'kyc_documents', 'tax_id',
        'business_registration', 'website', 'facebook', 'twitter', 'instagram',
        'youtube', 'is_active', 'is_featured', 'subscription_plan_id',
        'subscription_ends_at', 'total_ratings', 'total_products', 'total_sales',
        'revenue', 'join_date',
    ];

    protected function casts(): array
    {
        return [
            'kyc_documents' => 'json', 'is_active' => 'boolean', 'is_featured' => 'boolean',
            'subscription_ends_at' => 'datetime', 'join_date' => 'date', 'deleted_at' => 'datetime',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function ratings() { return $this->hasMany(VendorRating::class); }
    public function chats() { return $this->hasMany(VendorChat::class); }
    public function transactions() { return $this->hasMany(VendorTransaction::class); }
    public function payouts() { return $this->hasMany(VendorPayout::class); }
    public function subscriptions() { return $this->hasMany(VendorSubscription::class); }
    public function analytics() { return $this->hasMany(VendorAnalytic::class); }
    public function wallet() { return $this->hasOne(VendorWallet::class); }
    public function seoMetadata() { return $this->morphMany(SeoMetadata::class, 'metaable'); }
}
