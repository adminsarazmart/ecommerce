<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $subject;
    protected string $message;

    public function __construct(string $subject, string $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->greeting('Hello ' . ($notifiable->name ?? 'Customer') . '!')
            ->line($this->message)
            ->action('View Account', url('/account'));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'customer_notification',
            'subject' => $this->subject,
            'message' => $this->message,
        ];
    }
}
