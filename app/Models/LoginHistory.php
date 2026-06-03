<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginHistory extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'ip_address', 'user_agent', 'device', 'platform',
        'browser', 'location', 'is_successful', 'login_at',
    ];

    protected function casts(): array
    {
        return ['is_successful' => 'boolean', 'login_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
