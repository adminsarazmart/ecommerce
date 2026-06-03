<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    public function scopeSearch(Builder $query, string $term, array $columns): Builder
    {
        return $query->where(function ($q) use ($term, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'LIKE', "%{$term}%");
            }
        });
    }
}
