<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosSession extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'status', 'opened_at', 'closed_at', 'cash_start', 'cash_end',
        'cash_sales', 'card_sales', 'total_sales', 'notes',
    ];

    protected function casts(): array
    {
        return ['opened_at' => 'datetime', 'closed_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function posOrders() { return $this->hasMany(PosOrder::class, 'session_id'); }
}
