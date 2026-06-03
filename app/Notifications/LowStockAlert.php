<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Low Stock Alert')
            ->greeting('Hello ' . ($notifiable->name ?? 'Vendor') . '!')
            ->line('The following products are running low on stock:');

        foreach ($this->products as $product) {
            $mail->line('- ' . ($product['name'] ?? 'Unknown') . ' (SKU: ' . ($product['sku'] ?? 'N/A') . ') - Stock: ' . ($product['stock_quantity'] ?? 0));
        }

        return $mail->action('View Inventory', url('/vendor/inventory'));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'low_stock_alert',
            'products' => $this->products,
            'message' => count($this->products) . ' product(s) are running low on stock.',
        ];
    }
}
