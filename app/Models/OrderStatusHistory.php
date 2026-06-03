<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatusHistory extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['order_id', 'old_status', 'new_status', 'changed_by', 'notes'];

    public function order() { return $this->belongsTo(Order::class); }
    public function changedBy() { return $this->belongsTo(User::class, 'changed_by'); }
}
