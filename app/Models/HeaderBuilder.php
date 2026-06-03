<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeaderBuilder extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'layout', 'is_active', 'is_default'];

    protected function casts(): array
    {
        return ['layout' => 'json', 'is_active' => 'boolean', 'is_default' => 'boolean'];
    }
}
