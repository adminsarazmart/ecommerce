<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $role;

    public function __construct(string $role = 'customer')
    {
        $this->role = $role;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $dashboardUrl = match ($this->role) {
            'vendor' => url('/vendor/dashboard'),
            'admin' => url('/admin/dashboard'),
            'reseller' => url('/reseller/dashboard'),
            default => url('/account'),
        };

        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name'))
            ->greeting('Welcome ' . ($notifiable->name ?? 'User') . '!')
            ->line('Thank you for joining ' . config('app.name') . '.')
            ->line('We are excited to have you on board.')
            ->action('Get Started', $dashboardUrl)
            ->line('If you have any questions, feel free to contact us.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'welcome',
            'role' => $this->role,
            'message' => 'Welcome to ' . config('app.name') . '!',
        ];
    }
}
