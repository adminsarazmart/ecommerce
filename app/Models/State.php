<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['country_id', 'name', 'code'];

    public function country() { return $this->belongsTo(Country::class); }
    public function cities() { return $this->hasMany(City::class); }
}
