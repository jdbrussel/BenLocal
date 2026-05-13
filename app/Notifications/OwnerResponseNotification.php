<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerResponseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Review $review)
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
            ->subject(__('notifications.owner_response.subject'))
            ->line(__('notifications.owner_response.body', [
                'spot' => $this->review->spot->name
            ]))
            ->action(__('notifications.owner_response.action'), url('/reviews/' . $this->review->id))
            ->line(__('notifications.thank_you'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'owner_response',
            'review_id' => $this->review->id,
            'spot_name' => $this->review->spot->name,
            'message' => __('notifications.owner_response.body', [
                'spot' => $this->review->spot->name
            ]),
            'action_url' => '/reviews/' . $this->review->id,
        ];
    }
}
