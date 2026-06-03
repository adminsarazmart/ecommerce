<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'type', 'start_date', 'end_date', 'total_days',
        'reason', 'status', 'approved_by',
    ];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date'];
    }

    public function employee() { return $this->belongsTo(Employee::class); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
}
