<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DividendDistribution extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'period_start', 'period_end', 'total_profit', 'total_expenses',
        'net_profit', 'per_shareholder_amount', 'total_shareholders',
        'status', 'distributed_at',
    ];

    protected function casts(): array
    {
        return ['period_start' => 'date', 'period_end' => 'date', 'distributed_at' => 'datetime'];
    }

    public function payouts() { return $this->hasMany(DividendPayout::class, 'distribution_id'); }
}
