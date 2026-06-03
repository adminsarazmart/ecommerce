<?php

namespace App\Services;

use App\Repositories\EmployeeRepository;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;

class EmployeeService extends BaseService
{
    protected EmployeeRepository $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($repository);
    }

    public function manageAttendance(int $employeeId, string $date, string $clockIn, ?string $clockOut = null): Attendance
    {
        return Attendance::create([
            'employee_id' => $employeeId,
            'date' => $date,
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
        ]);
    }

    public function processPayroll(int $employeeId, string $period, float $amount, array $deductions = []): Payroll
    {
        DB::beginTransaction();
        try {
            $payroll = Payroll::create([
                'employee_id' => $employeeId,
                'period' => $period,
                'gross_amount' => $amount,
                'deductions' => $deductions,
                'net_amount' => $amount - array_sum($deductions),
                'status' => 'pending',
            ]);
            DB::commit();
            return $payroll;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function manageLeaves(int $employeeId, string $type, string $startDate, string $endDate, string $reason): LeaveRequest
    {
        return LeaveRequest::create([
            'employee_id' => $employeeId,
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reason' => $reason,
            'status' => 'pending',
        ]);
    }
}
