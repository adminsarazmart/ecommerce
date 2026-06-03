<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DividendPayout extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'distribution_id', 'shareholder_id', 'amount', 'percentage', 'status',
        'paid_at', 'payment_method', 'notes',
    ];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }

    public function distribution() { return $this->belongsTo(DividendDistribution::class, 'distribution_id'); }
    public function shareholder() { return $this->belongsTo(Shareholder::class); }
}
