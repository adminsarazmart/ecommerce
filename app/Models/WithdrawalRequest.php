<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawalRequest extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vendor_id', 'amount', 'fee', 'total', 'payment_method',
        'account_details', 'status', 'admin_note', 'processed_at',
    ];

    protected function casts(): array
    {
        return ['account_details' => 'json', 'processed_at' => 'datetime'];
    }

    public function vendor() { return $this->belongsTo(Vendor::class); }
}
