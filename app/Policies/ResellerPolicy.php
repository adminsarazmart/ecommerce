<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reseller;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResellerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('reseller.list');
    }

    public function view(User $user, Reseller $reseller): bool
    {
        if ($user->hasPermissionTo('reseller.view')) {
            return true;
        }
        return $user->hasRole('Reseller') && $user->reseller?->id === $reseller->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('reseller.create');
    }

    public function update(User $user, Reseller $reseller): bool
    {
        if ($user->hasPermissionTo('reseller.update')) {
            return true;
        }
        return $user->hasRole('Reseller') && $user->reseller?->id === $reseller->id;
    }

    public function delete(User $user, Reseller $reseller): bool
    {
        return $user->hasPermissionTo('reseller.delete');
    }

    public function approve(User $user, Reseller $reseller): bool
    {
        return $user->hasPermissionTo('reseller.approve');
    }

    public function restore(User $user, Reseller $reseller): bool
    {
        return $user->hasPermissionTo('reseller.restore');
    }

    public function forceDelete(User $user, Reseller $reseller): bool
    {
        return $user->hasPermissionTo('reseller.force-delete');
    }
}
