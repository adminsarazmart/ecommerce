<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeoMetadata extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'metaable_type', 'metaable_id', 'meta_title', 'meta_description',
        'meta_keywords', 'canonical_url', 'og_title', 'og_description',
        'og_image', 'robots',
    ];

    public function metaable() { return $this->morphTo(); }
}
