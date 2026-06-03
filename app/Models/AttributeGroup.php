<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeGroup extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'sort_order'];

    public function attributes() { return $this->hasMany(Attribute::class, 'group_id'); }
}
