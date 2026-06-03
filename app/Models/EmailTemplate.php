<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplate extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'subject', 'body', 'variables', 'is_active'];

    protected function casts(): array
    {
        return ['variables' => 'json', 'is_active' => 'boolean'];
    }
}
