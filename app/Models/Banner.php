<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'slug', 'type', 'image', 'link', 'title', 'subtitle',
        'button_text', 'position', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
