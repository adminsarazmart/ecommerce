<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'code', 'phone_code', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function states() { return $this->hasMany(State::class); }
}
