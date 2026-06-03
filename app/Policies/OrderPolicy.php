<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('order.list');
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->hasPermissionTo('order.view')) {
            return true;
        }
        if ($user->hasRole('Vendor')) {
            return $order->items()->where('vendor_id', $user->vendor?->id)->exists();
        }
        if ($user->hasRole('Customer')) {
            return $order->customer_id === $user->customer?->id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('order.create');
    }

    public function update(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('order.update');
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('order.delete');
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('order.restore');
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('order.force-delete');
    }

    public function updateStatus(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('order.update-status');
    }

    public function refund(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('order.refund');
    }
}
