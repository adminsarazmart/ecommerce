<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorSubscription extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vendor_id', 'plan_id', 'starts_at', 'ends_at', 'trial_ends_at',
        'status', 'payment_method', 'paid_amount',
    ];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'trial_ends_at' => 'datetime'];
    }

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function plan() { return $this->belongsTo(SubscriptionPlan::class, 'plan_id'); }
}
