<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['state_id', 'name'];

    public function state() { return $this->belongsTo(State::class); }
}
