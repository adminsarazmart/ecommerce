<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shareholder extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'shareholder_code', 'share_percentage', 'total_investment',
        'total_shares', 'join_date', 'status',
    ];

    protected function casts(): array
    {
        return ['join_date' => 'date'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function ledgers() { return $this->hasMany(ShareholderLedger::class); }
    public function dividendPayouts() { return $this->hasMany(DividendPayout::class); }
}
