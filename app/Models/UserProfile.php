<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserProfile extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'phone', 'avatar', 'gender', 'dob',
        'bio', 'address_line1', 'address_line2', 'city', 'state', 'zip', 'country',
        'nationality', 'metadata',
    ];

    protected function casts(): array
    {
        return ['dob' => 'date', 'metadata' => 'json'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
