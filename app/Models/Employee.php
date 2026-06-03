<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'employee_code', 'department_id', 'position', 'hire_date',
        'salary', 'salary_type', 'pay_rate', 'employment_status',
        'emergency_contact', 'emergency_phone', 'bank_name', 'bank_account',
        'bank_branch', 'documents',
    ];

    protected function casts(): array
    {
        return ['hire_date' => 'date', 'documents' => 'json'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function leaveRequests() { return $this->hasMany(LeaveRequest::class); }
    public function payrolls() { return $this->hasMany(Payroll::class); }
}
