<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomepageBuilder extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'sections', 'is_active', 'is_default'];

    protected function casts(): array
    {
        return ['sections' => 'json', 'is_active' => 'boolean', 'is_default' => 'boolean'];
    }
}
