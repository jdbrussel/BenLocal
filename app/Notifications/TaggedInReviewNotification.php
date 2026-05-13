<?php

namespace App\Notifications;

use App\Models\Review;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaggedInReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Review $review, public User $tagger)
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
            ->subject(__('notifications.tagged_in_review.subject'))
            ->line(__('notifications.tagged_in_review.body', [
                'name' => $this->tagger->name,
                'spot' => $this->review->spot->name
            ]))
            ->action(__('notifications.tagged_in_review.action'), url('/reviews/' . $this->review->id))
            ->line(__('notifications.thank_you'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'tagged_in_review',
            'review_id' => $this->review->id,
            'tagger_id' => $this->tagger->id,
            'spot_name' => $this->review->spot->name,
            'message' => __('notifications.tagged_in_review.body', [
                'name' => $this->tagger->name,
                'spot' => $this->review->spot->name
            ]),
            'action_url' => '/reviews/' . $this->review->id,
        ];
    }
}
