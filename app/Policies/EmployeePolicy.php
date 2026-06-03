<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('employee.list');
    }

    public function view(User $user, Employee $employee): bool
    {
        if ($user->hasPermissionTo('employee.view')) {
            return true;
        }
        return $user->hasRole('Employee') && $user->employee?->id === $employee->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('employee.create');
    }

    public function update(User $user, Employee $employee): bool
    {
        if ($user->hasPermissionTo('employee.update')) {
            return true;
        }
        return $user->hasRole('Employee') && $user->employee?->id === $employee->id;
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->hasPermissionTo('employee.delete');
    }

    public function restore(User $user, Employee $employee): bool
    {
        return $user->hasPermissionTo('employee.restore');
    }

    public function forceDelete(User $user, Employee $employee): bool
    {
        return $user->hasPermissionTo('employee.force-delete');
    }
}
