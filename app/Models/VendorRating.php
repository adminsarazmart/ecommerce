<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorRating extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['vendor_id', 'user_id', 'rating', 'review', 'is_approved'];

    protected function casts(): array
    {
        return ['is_approved' => 'boolean'];
    }

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function user() { return $this->belongsTo(User::class); }
}
