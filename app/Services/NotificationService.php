<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send a notification to a user if their preferences allow it.
     *
     * @param User $user
     * @param mixed $notification
     * @param string|null $preferenceKey
     * @return void
     */
    public function send(User $user, $notification, ?string $preferenceKey = null)
    {
        $preferences = $user->notificationPreferences()->firstOrCreate([
            'user_id' => $user->id
        ]);

        if ($preferenceKey && !$preferences->$preferenceKey) {
            return;
        }

        $user->notify($notification);
    }

    /**
     * Send notification to multiple users.
     *
     * @param \Illuminate\Support\Collection|array $users
     * @param mixed $notification
     * @param string|null $preferenceKey
     * @return void
     */
    public function sendToMultiple($users, $notification, ?string $preferenceKey = null)
    {
        foreach ($users as $user) {
            $this->send($user, $notification, $preferenceKey);
        }
    }
}
