<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'group_id', 'name', 'slug', 'type', 'is_required',
        'is_filterable', 'is_visible_on_product', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_required' => 'boolean', 'is_filterable' => 'boolean', 'is_visible_on_product' => 'boolean'];
    }

    public function group() { return $this->belongsTo(AttributeGroup::class, 'group_id'); }
    public function values() { return $this->hasMany(AttributeValue::class, 'attribute_id'); }
}
