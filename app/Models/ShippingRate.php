<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingRate extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'zone_id', 'name', 'min_weight', 'max_weight', 'min_total',
        'max_total', 'rate', 'additional_rate', 'estimated_days',
    ];

    public function zone() { return $this->belongsTo(ShippingZone::class, 'zone_id'); }
}
