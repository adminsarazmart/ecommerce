<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyPoint extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'customer_id', 'points', 'type', 'reference_type', 'reference_id',
        'description', 'expires_at',
    ];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime'];
    }

    public function customer() { return $this->belongsTo(Customer::class); }
}
