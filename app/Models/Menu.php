<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'location', 'items', 'is_active'];

    protected function casts(): array
    {
        return ['items' => 'json', 'is_active' => 'boolean'];
    }
}
