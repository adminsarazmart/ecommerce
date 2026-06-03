<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceGenerated extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $invoiceData;

    public function __construct(array $invoiceData)
    {
        $this->invoiceData = $invoiceData;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invoice Generated - #' . ($this->invoiceData['invoice_number'] ?? ''))
            ->greeting('Hello ' . ($notifiable->name ?? 'Customer') . '!')
            ->line('Your invoice has been generated.')
            ->line('Invoice: #' . ($this->invoiceData['invoice_number'] ?? 'N/A'))
            ->line('Amount: $' . number_format($this->invoiceData['amount'] ?? 0, 2))
            ->action('View Invoice', url('/account/invoices/' . ($this->invoiceData['id'] ?? 0)));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'invoice_generated',
            'invoice_id' => $this->invoiceData['id'] ?? null,
            'invoice_number' => $this->invoiceData['invoice_number'] ?? null,
            'amount' => $this->invoiceData['amount'] ?? 0,
            'message' => 'Invoice #' . ($this->invoiceData['invoice_number'] ?? '') . ' generated.',
        ];
    }
}
