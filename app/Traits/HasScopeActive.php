<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasScopeActive
{
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
