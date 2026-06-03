<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('vendor.list');
    }

    public function view(User $user, Vendor $vendor): bool
    {
        if ($user->hasPermissionTo('vendor.view')) {
            return true;
        }
        return $user->hasRole('Vendor') && $user->vendor?->id === $vendor->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('vendor.create');
    }

    public function update(User $user, Vendor $vendor): bool
    {
        if ($user->hasPermissionTo('vendor.update')) {
            return true;
        }
        return $user->hasRole('Vendor') && $user->vendor?->id === $vendor->id;
    }

    public function delete(User $user, Vendor $vendor): bool
    {
        return $user->hasPermissionTo('vendor.delete');
    }

    public function verify(User $user, Vendor $vendor): bool
    {
        return $user->hasPermissionTo('vendor.verify');
    }

    public function approveKyc(User $user, Vendor $vendor): bool
    {
        return $user->hasPermissionTo('vendor.approve-kyc');
    }

    public function restore(User $user, Vendor $vendor): bool
    {
        return $user->hasPermissionTo('vendor.restore');
    }

    public function forceDelete(User $user, Vendor $vendor): bool
    {
        return $user->hasPermissionTo('vendor.force-delete');
    }
}
