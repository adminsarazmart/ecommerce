<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'slides', 'is_active'];

    protected function casts(): array
    {
        return ['slides' => 'json', 'is_active' => 'boolean'];
    }
}
