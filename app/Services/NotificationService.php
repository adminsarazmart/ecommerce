<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Customer;
use App\Notifications\OrderConfirmation;
use App\Notifications\LowStockAlert;
use App\Notifications\VendorNotification;
use App\Notifications\CustomerNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function sendOrderConfirmation($notifiable, array $orderData): void
    {
        $notifiable->notify(new OrderConfirmation($orderData));
    }

    public function sendLowStockAlert(Vendor $vendor, array $products): void
    {
        $vendor->notify(new LowStockAlert($products));
    }

    public function sendVendorNotification(Vendor $vendor, string $subject, string $message): void
    {
        $vendor->notify(new VendorNotification($subject, $message));
    }

    public function sendCustomerNotification(Customer $customer, string $subject, string $message): void
    {
        $customer->notify(new CustomerNotification($subject, $message));
    }

    public function sendBulkNotification(string $type, array $recipients, array $data): void
    {
        $notifiableClass = match ($type) {
            'vendors' => Vendor::class,
            'customers' => Customer::class,
            default => User::class,
        };

        $notifiables = $notifiableClass::whereIn('id', $recipients)->get();

        foreach ($notifiables as $notifiable) {
            $notifiable->notify(new CustomerNotification(
                $data['subject'] ?? 'Notification',
                $data['message'] ?? ''
            ));
        }
    }
}
