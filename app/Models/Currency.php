<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'code', 'symbol', 'exchange_rate', 'is_default', 'is_active'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean', 'is_active' => 'boolean'];
    }
}
