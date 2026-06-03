<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        $query = Employee::with(['user', 'department']);

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(15);
        $departments = Department::orderBy('name')->get();

        return Inertia::render('Admin/Employees/Index', [
            'employees' => $employees,
            'departments' => $departments,
            'filters' => $request->only(['search', 'department_id', 'employment_status']),
        ]);
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return Inertia::render('Admin/Employees/Create', ['departments' => $departments]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password'] ?? 'password'),
            ]);

            $employee = Employee::create([
                'user_id' => $user->id,
                'employee_code' => $validated['employee_code'] ?? 'EMP-' . strtoupper(substr(uniqid(), -6)),
                'department_id' => $validated['department_id'],
                'position' => $validated['position'],
                'hire_date' => $validated['hire_date'],
                'salary' => $validated['salary'],
                'salary_type' => $validated['salary_type'] ?? 'monthly',
                'employment_status' => $validated['employment_status'] ?? 'active',
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_phone' => $validated['emergency_phone'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account' => $validated['bank_account'] ?? null,
            ]);

            $user->assignRole('Employee');

            activity()->performedOn($employee)->causedBy(auth()->user())->log('Employee created');

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');
        $departments = Department::orderBy('name')->get();

        return Inertia::render('Admin/Employees/Edit', [
            'employee' => $employee,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_code' => 'nullable|string|max:50|unique:employees,employee_code,' . $employee->id,
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'salary_type' => 'nullable|in:hourly,daily,weekly,monthly,yearly',
            'employment_status' => 'nullable|in:active,inactive,suspended,terminated',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:100',
        ]);

        try {
            $employee->update($validated);

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function attendance(Request $request)
    {
        $query = Attendance::with('employee.user');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $attendances = $query->latest()->paginate(15);

        return Inertia::render('Admin/Employees/Attendance', [
            'attendances' => $attendances,
            'filters' => $request->only(['date', 'employee_id']),
        ]);
    }

    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:present,absent,late,half_day,holiday',
        ]);

        try {
            $this->employeeService->manageAttendance(
                $validated['employee_id'],
                $validated['date'],
                $validated['clock_in'],
                $validated['clock_out'] ?? null
            );

            return redirect()->back()->with('success', 'Attendance recorded');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function leaves(Request $request)
    {
        $query = LeaveRequest::with('employee.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $leaves = $query->latest()->paginate(15);

        return Inertia::render('Admin/Employees/Leaves', [
            'leaves' => $leaves,
            'filters' => $request->only(['status', 'employee_id']),
        ]);
    }

    public function approveLeave(LeaveRequest $leave)
    {
        try {
            $leave->update(['status' => 'approved']);

            return redirect()->back()->with('success', 'Leave approved');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function rejectLeave(Request $request, LeaveRequest $leave)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        try {
            $leave->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
            ]);

            return redirect()->back()->with('success', 'Leave rejected');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function payroll(Request $request)
    {
        $query = Payroll::with('employee.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        $payrolls = $query->latest()->paginate(15);

        return Inertia::render('Admin/Employees/Payroll', [
            'payrolls' => $payrolls,
            'filters' => $request->only(['status', 'period']),
        ]);
    }

    public function storePayroll(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string|max:50',
            'gross_amount' => 'required|numeric|min:0',
            'deductions' => 'nullable|array',
            'deductions.*.label' => 'required|string',
            'deductions.*.amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $deductions = collect($validated['deductions'] ?? []);
            $totalDeductions = $deductions->sum('amount');
            $netAmount = $validated['gross_amount'] - $totalDeductions;

            Payroll::create([
                'employee_id' => $validated['employee_id'],
                'period' => $validated['period'],
                'gross_amount' => $validated['gross_amount'],
                'deductions' => $deductions->toArray(),
                'net_amount' => max(0, $netAmount),
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()->back()->with('success', 'Payroll created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function payPayroll(Payroll $payroll)
    {
        try {
            $payroll->update(['status' => 'paid']);

            activity()->performedOn($payroll)->causedBy(auth()->user())->log('Payroll processed');

            return redirect()->back()->with('success', 'Payroll marked as paid');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function performance()
    {
        $employees = Employee::with('user', 'department')
            ->withCount(['attendances as present_days' => function ($q) {
                $q->where('status', 'present');
            }])
            ->get();

        return Inertia::render('Admin/Employees/Performance', [
            'employees' => $employees,
        ]);
    }
}
