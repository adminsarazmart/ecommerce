<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxRate extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'rate', 'type', 'applies_to', 'country_id', 'state_id', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function country() { return $this->belongsTo(Country::class); }
    public function state() { return $this->belongsTo(State::class); }
}
