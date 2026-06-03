<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasCreatedBy
{
    public static function bootHasCreatedBy(): void
    {
        static::creating(function ($model) {
            if (empty($model->created_by) && auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
