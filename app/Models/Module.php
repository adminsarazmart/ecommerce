<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'alias', 'enabled', 'installed', 'version', 'options'];

    protected function casts(): array
    {
        return ['enabled' => 'boolean', 'installed' => 'boolean', 'options' => 'json'];
    }
}
