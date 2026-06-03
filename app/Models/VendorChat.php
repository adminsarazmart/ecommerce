<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorChat extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['vendor_id', 'user_id', 'message', 'is_read', 'attachment'];

    protected function casts(): array
    {
        return ['is_read' => 'boolean'];
    }

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function user() { return $this->belongsTo(User::class); }
}
