<?php

namespace App\Notifications;

use App\Models\Spot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HiddenGemTrendingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Spot $spot)
    {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($notifiable->notificationPreferences?->email_enabled) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.hidden_gem_trending.subject'))
            ->line(__('notifications.hidden_gem_trending.body', [
                'spot' => $this->spot->name
            ]))
            ->action(__('notifications.hidden_gem_trending.action'), url('/spots/' . $this->spot->id))
            ->line(__('notifications.thank_you'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'hidden_gem_trending',
            'spot_id' => $this->spot->id,
            'spot_name' => $this->spot->name,
            'message' => __('notifications.hidden_gem_trending.body', [
                'spot' => $this->spot->name
            ]),
            'action_url' => '/spots/' . $this->spot->id,
        ];
    }
}
