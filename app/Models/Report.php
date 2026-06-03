<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'slug', 'type', 'parameters', 'generated_by', 'file_path',
        'file_type', 'is_scheduled', 'schedule_cron', 'last_generated_at',
    ];

    protected function casts(): array
    {
        return ['parameters' => 'json', 'is_scheduled' => 'boolean', 'last_generated_at' => 'datetime'];
    }

    public function generatedBy() { return $this->belongsTo(User::class, 'generated_by'); }
}
