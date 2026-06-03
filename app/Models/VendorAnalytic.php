<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorAnalytic extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vendor_id', 'date', 'views', 'visits', 'orders', 'revenue',
        'commissions', 'conversion_rate', 'average_order_value',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function vendor() { return $this->belongsTo(Vendor::class); }
}
