<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipLevel extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'min_spend', 'points_required', 'discount_rate', 'benefits', 'is_active'];

    protected function casts(): array
    {
        return ['benefits' => 'json', 'is_active' => 'boolean'];
    }
}
