<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reseller extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'referral_code', 'parent_reseller_id', 'commission_rate',
        'total_earnings', 'total_withdrawn', 'current_balance', 'status', 'verified_at',
    ];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function parentReseller() { return $this->belongsTo(Reseller::class, 'parent_reseller_id'); }
    public function childResellers() { return $this->hasMany(Reseller::class, 'parent_reseller_id'); }
    public function transactions() { return $this->hasMany(ResellerTransaction::class); }
    public function affiliateLinks() { return $this->hasMany(AffiliateLink::class); }
}
