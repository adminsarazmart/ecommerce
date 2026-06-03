<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    return $user->can('view', \App\Models\Order::find($orderId));
});

Broadcast::channel('vendor.{vendorId}', function ($user, $vendorId) {
    return $user->vendor?->id === (int) $vendorId || $user->hasRole('admin');
});

Broadcast::channel('customer.{customerId}', function ($user, $customerId) {
    return $user->customer?->id === (int) $customerId;
});

Broadcast::channel('chat.{vendorId}.{customerId}', function ($user, $vendorId, $customerId) {
    return ($user->vendor?->id === (int) $vendorId) || ($user->customer?->id === (int) $customerId);
});

Broadcast::channel('admin.notifications', function ($user) {
    return $user->hasRole(['super_admin', 'admin']);
});

Broadcast::channel('vendor.{vendorId}.notifications', function ($user, $vendorId) {
    return $user->vendor?->id === (int) $vendorId;
});
