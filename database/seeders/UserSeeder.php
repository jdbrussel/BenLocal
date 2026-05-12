<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Follow;
use App\Models\NotificationPreference;
use App\Models\Region;
use App\Models\User;
use App\Models\UserRegionStatus;
use App\Models\UserReputation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tenerife = Region::where('slug', 'tenerife')->first();

        $usersData = [
            [
                'email' => 'jan@benlocal.test',
                'name' => 'Jan de Hollander',
                'community_slug' => 'netherlands',
                'city' => 'Costa Adeje',
                'preferred_language' => 'nl',
                'preferred_theme' => 'system',
                'local_status' => 'local',
                'trust_score' => 95.00,
            ],
            [
                'email' => 'carlos@benlocal.test',
                'name' => 'Carlos García',
                'community_slug' => 'spain-canaries',
                'city' => 'La Laguna',
                'preferred_language' => 'es',
                'preferred_theme' => 'light',
                'local_status' => 'local',
                'trust_score' => 98.00,
            ],
            [
                'email' => 'sofie@benlocal.test',
                'name' => 'Sofie Peeters',
                'community_slug' => 'belgium',
                'city' => 'Los Cristianos',
                'preferred_language' => 'nl',
                'preferred_theme' => 'dark',
                'local_status' => 'local',
                'trust_score' => 92.00,
            ],
            [
                'email' => 'emma@benlocal.test',
                'name' => 'Emma Smith',
                'community_slug' => 'united-kingdom',
                'city' => 'London',
                'preferred_language' => 'en',
                'preferred_theme' => 'system',
                'local_status' => 'tourist',
                'trust_score' => 75.00,
            ],
            [
                'email' => 'markus@benlocal.test',
                'name' => 'Markus Weber',
                'community_slug' => 'germany',
                'city' => 'Berlin',
                'preferred_language' => 'de',
                'preferred_theme' => 'light',
                'local_status' => 'regular_visitor',
                'trust_score' => 85.00,
            ],
            [
                'email' => 'marie@benlocal.test',
                'name' => 'Marie Dubois',
                'community_slug' => 'other',
                'preferred_language' => 'fr',
                'preferred_theme' => 'system',
                'local_status' => 'tourist',
                'trust_score' => 70.00,
            ],
        ];

        $users = [];
        foreach ($usersData as $data) {
            $community = Community::where('slug', $data['community_slug'])->first();

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'community_id' => $community?->id,
                    'city' => $data['city'] ?? null,
                    'preferred_language' => $data['preferred_language'],
                    'preferred_theme' => $data['preferred_theme'],
                    'residence_region_id' => $data['local_status'] === 'local' ? $tenerife->id : null,
                ]
            );

            $users[$data['email']] = $user;

            // User Region Status
            UserRegionStatus::updateOrCreate(
                ['user_id' => $user->id, 'region_id' => $tenerife->id],
                [
                    'status' => $data['local_status'],
                    'manually_verified' => $data['local_status'] === 'local',
                    'verified_at' => $data['local_status'] === 'local' ? now() : null,
                ]
            );

            // Notification Preferences
            NotificationPreference::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'email_enabled' => true,
                    'push_enabled' => true,
                ]
            );

            // User Reputation (Basic)
            UserReputation::updateOrCreate(
                ['user_id' => $user->id, 'region_id' => $tenerife->id],
                [
                    'local_status' => $data['local_status'],
                    'trust_score' => $data['trust_score'],
                    'community_id' => $community?->id,
                ]
            );
        }

        // Follows
        $follows = [
            ['follower' => 'emma@benlocal.test', 'followed' => 'jan@benlocal.test'],
            ['follower' => 'sofie@benlocal.test', 'followed' => 'carlos@benlocal.test'],
            ['follower' => 'jan@benlocal.test', 'followed' => 'sofie@benlocal.test'],
            ['follower' => 'markus@benlocal.test', 'followed' => 'jan@benlocal.test'],
        ];

        foreach ($follows as $f) {
            Follow::firstOrCreate([
                'follower_id' => $users[$f['follower']]->id,
                'followed_id' => $users[$f['followed']]->id,
            ]);
        }
    }
}
