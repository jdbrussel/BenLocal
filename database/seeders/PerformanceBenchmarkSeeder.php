<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerformanceBenchmarkSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Performance Benchmark Seeding...');

        $usersCount = (int) env('BENLOCAL_BENCHMARK_USERS', 500);
        $spotsCount = (int) env('BENLOCAL_BENCHMARK_SPOTS', 150);
        $reviewsCount = (int) env('BENLOCAL_BENCHMARK_REVIEWS', 2000);
        $recommendationsCount = (int) env('BENLOCAL_BENCHMARK_RECOMMENDATIONS', 500);
        $reactionsCount = (int) env('BENLOCAL_BENCHMARK_REACTIONS', 5000);
        $timelineCount = (int) env('BENLOCAL_BENCHMARK_TIMELINE_EVENTS', 10000);

        // 1. Users
        $this->command->info("Seeding {$usersCount} users...");
        $this->seedUsers($usersCount);

        // 2. Spots (Clusters)
        $this->callWith(LargeSpotDatasetSeeder::class, ['count' => $spotsCount]);

        // 3. Reviews
        $this->callWith(LargeReviewDatasetSeeder::class, ['count' => $reviewsCount]);

        // 4. Recommendations (Locals/Verified only)
        $this->seedRecommendations($recommendationsCount);

        // 5. Reactions
        $this->seedReactions($reactionsCount);

        // 6. Timeline
        $this->callWith(LargeTimelineDatasetSeeder::class, ['count' => $timelineCount]);

        // 7. Saved Spots
        $this->seedSavedSpots();

        // 8. Queue/Cache Scenarios
        $this->call(QueueJobDemoSeeder::class);
        $this->call(CacheScenarioSeeder::class);

        $this->command->info('Performance Benchmark Seeding Complete!');
    }

    private function seedUsers(int $count): void
    {
        $countries = ['Netherlands', 'Belgium', 'Spain', 'Germany', 'United Kingdom', 'France'];
        $batchSize = 500;
        $total = 0;

        while ($total < $count) {
            $batch = min($batchSize, $count - $total);
            $users = [];
            for ($i = 0; $i < $batch; $i++) {
                $users[] = [
                    'name' => "Benchmark User " . ($total + $i + 1),
                    'email' => "bench" . ($total + $i + 1) . "_" . time() . "@example.com",
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'preferred_language' => collect(['en', 'nl', 'es', 'de'])->random(),
                    'preferred_theme' => collect(['light', 'dark', 'system'])->random(),
                    'country' => collect($countries)->random(),
                    'city' => 'Benchmark City',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'role' => 'user', // Default
                ];
            }
            DB::table('users')->insert($users);
            $total += $batch;
            $this->command->comment("Seeded {$total}/{$count} users...");
        }

        // Add some specialized users
        User::factory()->count(10)->create(['role' => \App\Enums\UserRole::OWNER]);
        User::factory()->count(5)->create(['role' => \App\Enums\UserRole::SUPPORT]);
    }

    private function seedRecommendations(int $count): void
    {
        $this->command->info("Seeding {$count} recommendations...");
        $userIds = User::pluck('id')->toArray(); // All users for simplicity, filtered in logic if needed
        $spotIds = Spot::pluck('id')->toArray();

        $batchSize = 500;
        $total = 0;

        while ($total < $count) {
            $batch = min($batchSize, $count - $total);
            $recs = [];
            for ($i = 0; $i < $batch; $i++) {
                $recs[] = [
                    'user_id' => $userIds[array_rand($userIds)],
                    'spot_id' => $spotIds[array_rand($spotIds)],
                    'comment' => 'Recommended for performance testing.',
                    'confidence_score' => mt_rand(50, 100) / 100,
                    'is_active' => true,
                    'created_at' => now()->subDays(rand(0, 90)),
                    'updated_at' => now(),
                ];
            }
            // Use insert ignore or unique check if we strictly want one per user/spot
            DB::table('recommendations')->insertOrIgnore($recs);
            $total += $batch;
            $this->command->comment("Seeded {$total}/{$count} recommendations...");
        }
    }

    private function seedReactions(int $count): void
    {
        $this->command->info("Seeding {$count} reactions...");
        $userIds = User::pluck('id')->toArray();
        $reviewIds = Review::pluck('id')->toArray();

        $batchSize = 1000;
        $total = 0;

        while ($total < $count) {
            $batch = min($batchSize, $count - $total);
            $reactions = [];
            for ($i = 0; $i < $batch; $i++) {
                $reactions[] = [
                    'user_id' => $userIds[array_rand($userIds)],
                    'review_id' => $reviewIds[array_rand($reviewIds)],
                    'reaction_type' => collect(['agree', 'disagree', 'partly_agree', 'helpful'])->random(),
                    'created_at' => now()->subDays(rand(0, 60)),
                    'updated_at' => now(),
                ];
            }
            DB::table('review_reactions')->insertOrIgnore($reactions);
            $total += $batch;
            $this->command->comment("Seeded {$total}/{$count} reactions...");
        }
    }

    private function seedSavedSpots(): void
    {
        $this->command->info('Seeding saved spots patterns...');
        $userIds = User::limit(100)->pluck('id')->toArray();
        $spotIds = Spot::limit(500)->pluck('id')->toArray();

        $saved = [];
        foreach ($userIds as $userId) {
            $toSave = (array) array_rand($spotIds, rand(5, 15));
            foreach ($toSave as $spotIdx) {
                $saved[] = [
                    'user_id' => $userId,
                    'spot_id' => $spotIds[$spotIdx],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('saved_spots')->insertOrIgnore($saved);
    }
}
