<?php

namespace App\Notifications;

use App\Models\SpotClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BusinessClaimUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public SpotClaim $claim)
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
            ->subject(__('notifications.business_claim_update.subject'))
            ->line(__('notifications.business_claim_update.body', [
                'spot' => $this->claim->spot->name,
                'status' => $this->claim->status
            ]))
            ->action(__('notifications.business_claim_update.action'), url('/me/claims'))
            ->line(__('notifications.thank_you'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'business_claim_update',
            'claim_id' => $this->claim->id,
            'spot_name' => $this->claim->spot->name,
            'status' => $this->claim->status,
            'message' => __('notifications.business_claim_update.body', [
                'spot' => $this->claim->spot->name,
                'status' => $this->claim->status
            ]),
            'action_url' => '/me/claims',
        ];
    }
}
