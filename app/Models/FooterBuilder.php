<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FooterBuilder extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'columns', 'is_active', 'is_default'];

    protected function casts(): array
    {
        return ['columns' => 'json', 'is_active' => 'boolean', 'is_default' => 'boolean'];
    }
}
