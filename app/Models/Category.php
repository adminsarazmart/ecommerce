<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'icon', 'image', 'banner_image',
        'meta_title', 'meta_description', 'sort_order', 'is_active', 'display_mode',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function parent() { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children() { return $this->hasMany(Category::class, 'parent_id'); }
    public function products() { return $this->hasMany(Product::class); }
    public function translations() { return $this->hasMany(CategoryTranslation::class); }
}
