<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Spot;
use App\Models\Review;
use App\Models\Region;
use App\Models\Campaign;
use App\Notifications\NewFollowerNotification;
use App\Notifications\TaggedInReviewNotification;
use App\Notifications\ReviewValidatedNotification;
use App\Notifications\LocalStatusApprovedNotification;
use App\Notifications\HiddenGemTrendingNotification;
use App\Notifications\CampaignSelectionNotification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $spots = Spot::all();
        $reviews = Review::all();
        $regions = Region::all();
        $campaigns = Campaign::all();

        if ($users->count() < 2) return;

        foreach ($users->take(5) as $user) {
            // Ensure preferences exist
            $user->notificationPreferences()->firstOrCreate(['user_id' => $user->id]);

            // Scenario 1: Unread notifications
            $user->notify(new NewFollowerNotification($users->where('id', '!=', $user->id)->random()));

            if ($reviews->isNotEmpty()) {
                $user->notify(new TaggedInReviewNotification($reviews->random(), $users->where('id', '!=', $user->id)->random()));
            }

            // Scenario 2: Read notifications
            if ($reviews->isNotEmpty()) {
                $user->notify(new ReviewValidatedNotification($reviews->random()));
                $user->unreadNotifications()->first()->markAsRead();
            }

            // Scenario 3: Multiple types
            if ($regions->isNotEmpty()) {
                $user->notify(new LocalStatusApprovedNotification($regions->random()));
            }

            if ($spots->isNotEmpty()) {
                $user->notify(new HiddenGemTrendingNotification($spots->random()));
            }

            if ($campaigns->isNotEmpty()) {
                $user->notify(new CampaignSelectionNotification($campaigns->random()));
            }
        }

        // Scenario 4: Specific preference scenarios
        $prefUser = $users->last();
        if ($prefUser) {
            $prefUser->notificationPreferences()->updateOrCreate(
                ['user_id' => $prefUser->id],
                [
                    'new_followers' => false,
                    'email_enabled' => false,
                ]
            );
        }
    }
}
