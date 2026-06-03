<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceToken extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'device_id', 'device_name', 'device_type', 'fcm_token',
        'access_token', 'refresh_token', 'expires_at', 'last_used_at',
    ];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime', 'last_used_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
