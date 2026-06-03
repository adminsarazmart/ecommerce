<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'slug'];

    public function products() { return $this->belongsToMany(Product::class, 'product_tag'); }
}
