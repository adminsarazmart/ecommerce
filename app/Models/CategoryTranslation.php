<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryTranslation extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['category_id', 'locale', 'name', 'description', 'meta_title', 'meta_description'];

    public function category() { return $this->belongsTo(Category::class); }
}
