<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Spot;
use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\TimelineEvent;
use App\Models\Recommendation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BenchmarkSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Starting benchmark seeding (this may take a while)...');

        // 1. Users (5,000)
        $this->command->info('Seeding 5,000 users...');
        User::factory()->count(5000)->create();

        // 2. Spots (1,000) - Assuming we have enough categories/regions seeded already
        $this->command->info('Seeding 1,000 spots...');
        Spot::factory()->count(1000)->create();

        $userIds = User::pluck('id')->toArray();
        $spotIds = Spot::pluck('id')->toArray();

        // 3. Reviews (20,000)
        $this->command->info('Seeding 20,000 reviews...');
        for ($i = 0; $i < 20; $i++) {
            Review::factory()->count(1000)->create([
                'user_id' => function() use ($userIds) { return $userIds[array_rand($userIds)]; },
                'spot_id' => function() use ($spotIds) { return $spotIds[array_rand($spotIds)]; },
            ]);
        }

        $reviewIds = Review::pluck('id')->toArray();

        // 4. Review Reactions (50,000)
        $this->command->info('Seeding 50,000 review reactions...');
        for ($i = 0; $i < 50; $i++) {
            ReviewReaction::factory()->count(1000)->create([
                'user_id' => function() use ($userIds) { return $userIds[array_rand($userIds)]; },
                'review_id' => function() use ($reviewIds) { return $reviewIds[array_rand($reviewIds)]; },
            ]);
        }

        // 5. Timeline Events (100,000)
        $this->command->info('Seeding 100,000 timeline events...');
        for ($i = 0; $i < 100; $i++) {
            TimelineEvent::factory()->count(1000)->create([
                'user_id' => function() use ($userIds) { return $userIds[array_rand($userIds)]; },
            ]);
        }

        $this->command->info('Benchmark seeding complete.');
    }
}
