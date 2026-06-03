<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingZone extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'countries', 'states', 'zip_codes', 'is_active'];

    protected function casts(): array
    {
        return ['countries' => 'json', 'states' => 'json', 'zip_codes' => 'json', 'is_active' => 'boolean'];
    }

    public function rates() { return $this->hasMany(ShippingRate::class, 'zone_id'); }
}
