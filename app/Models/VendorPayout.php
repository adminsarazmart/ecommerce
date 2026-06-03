<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorPayout extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'amount', 'fee', 'total', 'payment_method',
        'account_details', 'status', 'notes', 'processed_at', 'approved_by',
    ];

    protected function casts(): array
    {
        return ['account_details' => 'json', 'processed_at' => 'datetime'];
    }

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
}
