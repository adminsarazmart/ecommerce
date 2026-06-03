<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'logo', 'description', 'website', 'meta_title',
        'meta_description', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function products() { return $this->hasMany(Product::class); }
}
