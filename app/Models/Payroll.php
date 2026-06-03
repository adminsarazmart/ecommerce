<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'pay_period_start', 'pay_period_end', 'basic_salary',
        'allowances', 'deductions', 'overtime_pay', 'bonus', 'tax', 'net_pay',
        'total', 'status', 'paid_at', 'payment_method',
    ];

    protected function casts(): array
    {
        return [
            'pay_period_start' => 'date', 'pay_period_end' => 'date',
            'allowances' => 'json', 'deductions' => 'json', 'paid_at' => 'datetime',
        ];
    }

    public function employee() { return $this->belongsTo(Employee::class); }
}
