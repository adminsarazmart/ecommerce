<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected string $slugSourceField = 'name';

    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $source = $model->{$model->slugSourceField} ?? $model->name;
                $model->slug = Str::slug($source);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->slugSourceField) && !$model->isDirty('slug')) {
                $model->slug = Str::slug($model->{$model->slugSourceField});
            }
        });
    }
}
