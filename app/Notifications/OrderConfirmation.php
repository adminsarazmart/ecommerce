<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $orderData;

    public function __construct(array $orderData)
    {
        $this->orderData = $orderData;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Confirmation - #' . ($this->orderData['order_number'] ?? ''))
            ->greeting('Hello ' . ($notifiable->name ?? 'Customer') . '!')
            ->line('Your order has been confirmed.')
            ->line('Order Number: ' . ($this->orderData['order_number'] ?? 'N/A'))
            ->line('Total: $' . number_format($this->orderData['total'] ?? 0, 2))
            ->action('View Order', url('/account/orders/' . ($this->orderData['id'] ?? 0)))
            ->line('Thank you for your purchase!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'order_confirmation',
            'order_id' => $this->orderData['id'] ?? null,
            'order_number' => $this->orderData['order_number'] ?? null,
            'total' => $this->orderData['total'] ?? 0,
            'message' => 'Order #' . ($this->orderData['order_number'] ?? '') . ' confirmed successfully.',
        ];
    }
}
