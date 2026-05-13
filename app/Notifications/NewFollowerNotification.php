<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $follower)
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
            ->subject(__('notifications.new_follower.subject'))
            ->line(__('notifications.new_follower.body', ['name' => $this->follower->name]))
            ->action(__('notifications.new_follower.action'), url('/users/' . $this->follower->id))
            ->line(__('notifications.thank_you'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_follower',
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'message' => __('notifications.new_follower.body', ['name' => $this->follower->name]),
            'action_url' => '/users/' . $this->follower->id,
        ];
    }
}
