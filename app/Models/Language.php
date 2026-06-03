<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'code', 'is_default', 'is_active', 'rtl'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean', 'is_active' => 'boolean', 'rtl' => 'boolean'];
    }
}
