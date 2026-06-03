<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemeSetting extends BaseModel
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'group', 'type'];
}
