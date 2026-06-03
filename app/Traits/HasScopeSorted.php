<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasScopeSorted
{
    public function scopeSorted(Builder $query, string $column = 'sort_order', string $direction = 'asc'): Builder
    {
        return $query->orderBy($column, $direction);
    }
}
