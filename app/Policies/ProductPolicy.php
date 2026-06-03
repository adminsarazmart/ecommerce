<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('product.list');
    }

    public function view(User $user, Product $product): bool
    {
        return $user->hasPermissionTo('product.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('product.create');
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->hasPermissionTo('product.update')) {
            if ($user->hasRole('Vendor')) {
                return $product->vendor_id === $user->vendor?->id;
            }
            return true;
        }
        return false;
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->hasPermissionTo('product.delete')) {
            if ($user->hasRole('Vendor')) {
                return $product->vendor_id === $user->vendor?->id;
            }
            return true;
        }
        return false;
    }

    public function restore(User $user, Product $product): bool
    {
        return $user->hasPermissionTo('product.restore');
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return $user->hasPermissionTo('product.force-delete');
    }
}
