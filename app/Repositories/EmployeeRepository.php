<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Collection;

class EmployeeRepository extends BaseRepository
{
    public function __construct(Employee $employee)
    {
        parent::__construct($employee);
    }

    public function findByDepartment(int $departmentId): Collection
    {
        return $this->model->where('department_id', $departmentId)->get();
    }

    public function findByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function getAttendance(int $employeeId, string $month): Collection
    {
        return Attendance::where('employee_id', $employeeId)
            ->whereMonth('date', date('m', strtotime($month)))
            ->whereYear('date', date('Y', strtotime($month)))
            ->get();
    }
}
