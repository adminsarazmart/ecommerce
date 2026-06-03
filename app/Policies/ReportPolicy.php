<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('report.list');
    }

    public function viewSales(User $user): bool
    {
        return $user->hasPermissionTo('report.sales');
    }

    public function viewProfit(User $user): bool
    {
        return $user->hasPermissionTo('report.profit');
    }

    public function viewInventory(User $user): bool
    {
        return $user->hasPermissionTo('report.inventory');
    }

    public function viewVendors(User $user): bool
    {
        return $user->hasPermissionTo('report.vendors');
    }

    public function viewCustomers(User $user): bool
    {
        return $user->hasPermissionTo('report.customers');
    }

    public function export(User $user): bool
    {
        return $user->hasPermissionTo('report.export');
    }
}
