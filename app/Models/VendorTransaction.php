<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorTransaction extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vendor_id', 'amount', 'type', 'description', 'reference_type',
        'reference_id', 'balance_before', 'balance_after', 'status',
    ];

    public function vendor() { return $this->belongsTo(Vendor::class); }
}
