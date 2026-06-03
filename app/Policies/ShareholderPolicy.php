<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Shareholder;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShareholderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('shareholder.list');
    }

    public function view(User $user, Shareholder $shareholder): bool
    {
        if ($user->hasPermissionTo('shareholder.view')) {
            return true;
        }
        return $user->hasRole('Shareholder') && $user->shareholder?->id === $shareholder->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('shareholder.create');
    }

    public function update(User $user, Shareholder $shareholder): bool
    {
        return $user->hasPermissionTo('shareholder.update');
    }

    public function delete(User $user, Shareholder $shareholder): bool
    {
        return $user->hasPermissionTo('shareholder.delete');
    }

    public function distributeDividends(User $user): bool
    {
        return $user->hasPermissionTo('shareholder.distribute-dividends');
    }

    public function restore(User $user, Shareholder $shareholder): bool
    {
        return $user->hasPermissionTo('shareholder.restore');
    }

    public function forceDelete(User $user, Shareholder $shareholder): bool
    {
        return $user->hasPermissionTo('shareholder.force-delete');
    }
}
