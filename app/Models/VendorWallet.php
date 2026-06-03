<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorWallet extends BaseModel
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'balance', 'pending_balance', 'total_earned', 'total_withdrawn', 'currency'];

    public function vendor() { return $this->belongsTo(Vendor::class); }
}
