<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnnouncementBar extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'link', 'link_text', 'bg_color', 'text_color',
        'start_at', 'end_at', 'active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['start_at' => 'datetime', 'end_at' => 'datetime', 'active' => 'boolean'];
    }
}
