<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content', 'meta_title', 'meta_description',
        'is_active', 'is_default', 'layout',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_default' => 'boolean'];
    }
}
