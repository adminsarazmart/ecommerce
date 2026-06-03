<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('customer.list');
    }

    public function view(User $user, Customer $customer): bool
    {
        if ($user->hasPermissionTo('customer.view')) {
            return true;
        }
        return $user->hasRole('Customer') && $user->customer?->id === $customer->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('customer.create');
    }

    public function update(User $user, Customer $customer): bool
    {
        if ($user->hasPermissionTo('customer.update')) {
            return true;
        }
        return $user->hasRole('Customer') && $user->customer?->id === $customer->id;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('customer.delete');
    }

    public function restore(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('customer.restore');
    }

    public function forceDelete(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('customer.force-delete');
    }
}
