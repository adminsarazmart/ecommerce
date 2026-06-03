<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'employee_id', 'date', 'clock_in', 'clock_out', 'status',
        'worked_hours', 'overtime_hours', 'notes',
    ];

    protected function casts(): array
    {
        return ['date' => 'date', 'clock_in' => 'datetime', 'clock_out' => 'datetime'];
    }

    public function employee() { return $this->belongsTo(Employee::class); }
}
