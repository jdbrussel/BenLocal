<?php

namespace Database\Seeders;

use App\Models\CookieConsent;
use App\Models\GdprDeletion;
use App\Models\GdprExport;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            NotificationPreference::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'new_followers' => true,
                    'review_replies' => true,
                    'recommendation_validation' => true,
                    'tagged_in_review' => true,
                    'hidden_gem_updates' => true,
                    'local_status_updates' => true,
                    'spot_updates' => true,
                    'marketing' => false,
                    'email_enabled' => true,
                    'push_enabled' => true,
                ]
            );

            // Seed some cookie consents
            CookieConsent::create([
                'user_id' => $user->id,
                'necessary' => true,
                'analytics' => true,
                'consented_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // GDPR Examples
        if ($users->isNotEmpty()) {
            $userForExport = $users->first();
            GdprExport::create([
                'user_id' => $userForExport->id,
                'requested_at' => now()->subDays(2),
                'completed_at' => now()->subDay(),
                'export_path' => 'exports/gdpr-' . $userForExport->id . '.json',
            ]);

            $userForDeletion = $users->last();
            GdprDeletion::create([
                'user_id' => $userForDeletion->id,
                'requested_at' => now()->subDays(5),
            ]);
        }
    }
}
