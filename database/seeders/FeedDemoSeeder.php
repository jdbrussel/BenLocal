<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jan = \App\Models\User::where('email', 'jan@benlocal.test')->first();
        $sofie = \App\Models\User::where('email', 'sofie@benlocal.test')->first();
        $carlos = \App\Models\User::where('email', 'carlos@benlocal.test')->first();
        $emma = \App\Models\User::where('email', 'emma@benlocal.test')->first();
        $markus = \App\Models\User::where('email', 'markus@benlocal.test')->first();

        $tenerife = \App\Models\Region::where('slug', 'tenerife')->first();
        $spots = \App\Models\Spot::all();

        // 1. Jan's Scenario
        if ($jan) {
            // Jan's recommendations
            $janSpots = $spots->random(3);
            foreach ($janSpots as $spot) {
                \App\Models\TimelineEvent::create([
                    'user_id' => $jan->id,
                    'type' => 'recommendation_created',
                    'region_id' => $spot->region_id ?? $tenerife->id,
                    'payload' => ['spot_id' => $spot->id, 'spot_name' => $spot->name, 'user_name' => $jan->name, 'hidden_gem_candidate' => true],
                    'created_at' => now()->subDays(2),
                ]);
            }
            // Jan tagged in reviews
            $reviewer = \App\Models\User::where('id', '!=', $jan->id)->get()->random();
            \App\Models\TimelineEvent::create([
                'user_id' => $jan->id,
                'type' => 'user_tagged_in_review',
                'payload' => ['spot_name' => $spots->random()->name, 'author_name' => $reviewer->name, 'tagged_user_name' => $jan->name],
                'created_at' => now()->subHours(5),
            ]);
        }

        // 2. Sofie's Scenario (Belgium)
        if ($sofie) {
            $cafeVlaanderen = $spots->filter(fn($s) => str_contains(strtolower($s->name), 'vlaanderen'))->first() ?? $spots->random();
            \App\Models\TimelineEvent::create([
                'user_id' => $sofie->id,
                'type' => 'review_created',
                'payload' => ['spot_id' => $cafeVlaanderen->id, 'spot_name' => $cafeVlaanderen->name, 'rating' => 5, 'verified_visit' => true],
                'created_at' => now()->subDays(1),
            ]);
        }

        // 3. Carlos's Scenario (Canarian Hidden Gems)
        if ($carlos) {
            $guachinche = $spots->filter(fn($s) => str_contains(strtolower($s->name), 'guachinche'))->first() ?? $spots->random();
            \App\Models\TimelineEvent::create([
                'user_id' => $carlos->id,
                'type' => 'hidden_gem_detected',
                'payload' => ['spot_id' => $guachinche->id, 'spot_name' => $guachinche->name, 'reason' => 'Authentic local favorite'],
                'created_at' => now()->subHours(12),
            ]);
        }

        // 4. Emma's Scenario
        if ($emma) {
            // Emma saves spots
            $emmaSaves = $spots->random(5);
            foreach ($emmaSaves as $spot) {
                \App\Models\TimelineEvent::create([
                    'user_id' => $emma->id,
                    'type' => 'spot_saved',
                    'payload' => ['spot_id' => $spot->id, 'spot_name' => $spot->name, 'user_name' => $emma->name],
                    'created_at' => now()->subDays(rand(1, 7)),
                ]);
            }
        }

        // 5. Markus's Scenario
        if ($markus && $jan) {
            // Markus follows Jan
            \App\Models\TimelineEvent::create([
                'user_id' => $markus->id,
                'type' => 'user_followed',
                'payload' => ['follower_name' => $markus->name, 'followed_name' => $jan->name, 'follower_id' => $markus->id, 'followed_id' => $jan->id],
                'created_at' => now()->subDays(10),
            ]);
        }

        // Community-specific events
        $communities = \App\Models\Community::all();
        foreach ($communities as $community) {
            $commUser = \App\Models\User::where('community_id', $community->id)->first();
            if ($commUser) {
                \App\Models\TimelineEvent::create([
                    'user_id' => $commUser->id,
                    'type' => 'recommendation_created',
                    'payload' => ['spot_name' => $spots->random()->name, 'community' => $community->name],
                    'created_at' => now()->subHours(rand(1, 24)),
                ]);
            }
        }
    }
}
