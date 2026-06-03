<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasDateRange
{
    public function scopeDateRange(Builder $query, string $start, string $end, string $column = 'created_at'): Builder
    {
        return $query->whereDate($column, '>=', $start)
            ->whereDate($column, '<=', $end);
    }
}
