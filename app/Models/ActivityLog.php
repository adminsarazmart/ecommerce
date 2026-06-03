<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'log_name', 'description', 'subject_type', 'subject_id',
        'causer_type', 'causer_id', 'properties', 'event', 'batch_uuid',
    ];

    protected function casts(): array
    {
        return ['properties' => 'json'];
    }

    public function subject() { return $this->morphTo(); }
    public function causer() { return $this->morphTo(); }
}
