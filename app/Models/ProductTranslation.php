<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductTranslation extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['product_id', 'locale', 'name', 'description', 'short_description', 'meta_title', 'meta_description'];

    public function product() { return $this->belongsTo(Product::class); }
}
