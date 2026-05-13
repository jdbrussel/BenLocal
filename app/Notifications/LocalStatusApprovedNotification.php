<?php

namespace App\Notifications;

use App\Models\Region;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LocalStatusApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Region $region)
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
            ->subject(__('notifications.local_status_approved.subject'))
            ->line(__('notifications.local_status_approved.body', [
                'region' => $this->region->name
            ]))
            ->action(__('notifications.local_status_approved.action'), url('/me/profile'))
            ->line(__('notifications.thank_you'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'local_status_approved',
            'region_id' => $this->region->id,
            'region_name' => $this->region->name,
            'message' => __('notifications.local_status_approved.body', [
                'region' => $this->region->name
            ]),
            'action_url' => '/me/profile',
        ];
    }
}
