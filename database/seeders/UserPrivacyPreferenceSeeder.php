<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserPrivacyPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Fully public profile
        User::factory()->create([
            'name' => 'Public Paula',
            'profile_visibility' => 'public',
            'show_location' => true,
            'show_reviews' => true,
        ]);

        // 2. Private profile
        User::factory()->create([
            'name' => 'Private Pete',
            'profile_visibility' => 'private',
            'show_location' => false,
            'show_reviews' => false,
        ]);

        // 3. Reviews hidden
        User::factory()->create([
            'name' => 'NoReviews Rick',
            'profile_visibility' => 'public',
            'show_reviews' => false,
        ]);

        // 4. Friends only profile
        User::factory()->create([
            'name' => 'FriendsOnly Fiona',
            'profile_visibility' => 'friends',
            'show_location' => true,
            'show_reviews' => true,
        ]);

        // 5. Public trusted local profile
        User::factory()->create([
            'name' => 'Local Larry',
            'role' => \App\Enums\UserRole::USER,
            'local_status_verified_at' => now(),
            'profile_visibility' => 'public',
            'show_location' => true,
            'show_reviews' => true,
        ]);

        // 6. Business owner visibility profile
        User::factory()->create([
            'name' => 'Owner Owen',
            'role' => \App\Enums\UserRole::OWNER,
            'profile_visibility' => 'public',
            'show_location' => true,
            'show_reviews' => true,
        ]);

        // Randomize some existing users
        User::whereNull('profile_visibility')->each(function ($user) {
            $visibilities = ['public', 'private', 'friends'];
            $user->update([
                'profile_visibility' => $visibilities[array_rand($visibilities)],
                'show_location' => (bool)rand(0, 1),
                'show_reviews' => (bool)rand(0, 1),
            ]);
        });
    }
}
