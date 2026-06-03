<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Status Updated - #' . ($this->data['order_number'] ?? ''))
            ->greeting('Hello ' . ($notifiable->name ?? 'Customer') . '!')
            ->line('Your order status has been updated.')
            ->line('Order: #' . ($this->data['order_number'] ?? 'N/A'))
            ->line('Status: ' . ($this->data['status'] ?? 'N/A'))
            ->action('View Order', url('/account/orders/' . ($this->data['id'] ?? 0)));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'order_status',
            'order_id' => $this->data['id'] ?? null,
            'order_number' => $this->data['order_number'] ?? null,
            'status' => $this->data['status'] ?? null,
            'message' => 'Order #' . ($this->data['order_number'] ?? '') . ' is now ' . ($this->data['status'] ?? '') . '.',
        ];
    }
}
