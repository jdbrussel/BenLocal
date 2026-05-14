<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Review;
use App\Models\Recommendation;
use App\Models\Spot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnonymizedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spots = Spot::all();
        if ($spots->isEmpty()) return;

        $communities = \App\Models\Community::all();
        if ($communities->isEmpty()) return;

        // Create 5-10 anonymized user scenarios
        for ($i = 1; $i <= 8; $i++) {
            $user = User::factory()->create([
                'name' => 'Deleted User',
                'email' => 'deleted-' . Str::random(10) . '@example.com',
                'password' => bcrypt(Str::random(16)),
                'avatar' => null,
                'provider' => null,
                'provider_id' => null,
                'email_verified_at' => null,
                'onboarding_completed' => false,
                'deleted_at' => now(), // Soft deleted
                'community_id' => $communities->random()->id,
            ]);

            // Ensure reviews remain visible anonymously
            for ($j = 0; $j < rand(2, 5); $j++) {
                Review::factory()->create([
                    'user_id' => $user->id,
                    'spot_id' => $spots->random()->id,
                    'user_community_id' => $communities->random()->id,
                ]);
            }

            // Ensure recommendations remain visible anonymously
            for ($j = 0; $j < rand(1, 3); $j++) {
                Recommendation::factory()->create([
                    'user_id' => $user->id,
                    'spot_id' => $spots->random()->id,
                    'community_id' => $communities->random()->id,
                ]);
            }
        }

        // Specific scenarios

        // Scenario: User with many reviews deleted
        $manyReviewsUser = User::factory()->create([
            'name' => 'Deleted User',
            'email' => 'deleted-heavy-' . Str::random(10) . '@example.com',
            'avatar' => null,
            'deleted_at' => now(),
            'community_id' => $communities->random()->id,
        ]);
        for ($j = 0; $j < 15; $j++) {
            Review::factory()->create([
                'user_id' => $manyReviewsUser->id,
                'spot_id' => $spots->random()->id,
                'user_community_id' => $communities->random()->id,
            ]);
        }

        // Scenario: Business owner deleted account
        $owner = User::factory()->create([
            'name' => 'Deleted User',
            'email' => 'deleted-owner-' . Str::random(10) . '@example.com',
            'role' => \App\Enums\UserRole::OWNER,
            'avatar' => null,
            'deleted_at' => now(),
            'community_id' => $communities->random()->id,
        ]);

        // Scenario: Trusted local deleted account
        $local = User::factory()->create([
            'name' => 'Deleted User',
            'email' => 'deleted-local-' . Str::random(10) . '@example.com',
            'avatar' => null,
            'local_status_verified_at' => now()->subYear(),
            'deleted_at' => now(),
            'community_id' => $communities->random()->id,
        ]);
    }
}
