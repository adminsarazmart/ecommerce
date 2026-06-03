<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutProcessed extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $payoutData;

    public function __construct(array $payoutData)
    {
        $this->payoutData = $payoutData;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payout Processed Successfully')
            ->greeting('Hello ' . ($notifiable->name ?? 'Vendor') . '!')
            ->line('Your payout has been processed.')
            ->line('Amount: $' . number_format($this->payoutData['amount'] ?? 0, 2))
            ->line('Status: ' . ($this->payoutData['status'] ?? 'completed'))
            ->action('View Wallet', url('/vendor/wallet'));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'payout_processed',
            'payout_id' => $this->payoutData['id'] ?? null,
            'amount' => $this->payoutData['amount'] ?? 0,
            'status' => $this->payoutData['status'] ?? 'completed',
            'message' => 'Payout of $' . number_format($this->payoutData['amount'] ?? 0, 2) . ' processed.',
        ];
    }
}
