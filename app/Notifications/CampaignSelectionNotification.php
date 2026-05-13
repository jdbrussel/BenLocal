<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CampaignSelectionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Campaign $campaign)
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
            ->subject(__('notifications.campaign_selection.subject'))
            ->line(__('notifications.campaign_selection.body', [
                'campaign' => $this->campaign->title
            ]))
            ->action(__('notifications.campaign_selection.action'), url('/campaigns/' . $this->campaign->id))
            ->line(__('notifications.thank_you'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'campaign_selection',
            'campaign_id' => $this->campaign->id,
            'campaign_title' => $this->campaign->title,
            'message' => __('notifications.campaign_selection.body', [
                'campaign' => $this->campaign->title
            ]),
            'action_url' => '/campaigns/' . $this->campaign->id,
        ];
    }
}
