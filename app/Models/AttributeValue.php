<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeValue extends BaseModel
{
    use HasFactory;

    protected $fillable = ['attribute_id', 'value', 'slug', 'color_code', 'image', 'sort_order'];

    public function attribute() { return $this->belongsTo(Attribute::class, 'attribute_id'); }
}
