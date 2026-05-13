<?php

namespace App\Notifications;

use App\Models\Recommendation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecommendationConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Recommendation $recommendation)
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
            ->subject(__('notifications.recommendation_confirmed.subject'))
            ->line(__('notifications.recommendation_confirmed.body', [
                'spot' => $this->recommendation->spot->name
            ]))
            ->action(__('notifications.recommendation_confirmed.action'), url('/spots/' . $this->recommendation->spot->id))
            ->line(__('notifications.thank_you'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'recommendation_confirmed',
            'recommendation_id' => $this->recommendation->id,
            'spot_id' => $this->recommendation->spot->id,
            'spot_name' => $this->recommendation->spot->name,
            'message' => __('notifications.recommendation_confirmed.body', [
                'spot' => $this->recommendation->spot->name
            ]),
            'action_url' => '/spots/' . $this->recommendation->spot->id,
        ];
    }
}
