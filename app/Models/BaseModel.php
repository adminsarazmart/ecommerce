<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasUuid;

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSorted($query, string $column = 'sort_order', string $direction = 'asc')
    {
        return $query->orderBy($column, $direction);
    }

    protected function formatCurrency($value): string
    {
        return number_format((float) $value, 2);
    }

    protected function formatPercentage($value): string
    {
        return number_format((float) $value, 2) . '%';
    }

    protected function formatDate($date): ?string
    {
        if (!$date) {
            return null;
        }

        return $date instanceof \Carbon\Carbon
            ? $date->format('Y-m-d')
            : \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    protected function formatDateTime($date): ?string
    {
        if (!$date) {
            return null;
        }

        return $date instanceof \Carbon\Carbon
            ? $date->toDateTimeString()
            : \Carbon\Carbon::parse($date)->toDateTimeString();
    }
}
