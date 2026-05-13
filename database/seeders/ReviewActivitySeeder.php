<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = \App\Models\Review::with(['user', 'spot'])->get();
        $tenerife = \App\Models\Region::where('slug', 'tenerife')->first();

        foreach ($reviews as $review) {
            \App\Models\TimelineEvent::create([
                'user_id' => $review->user_id,
                'type' => 'review_created',
                'eventable_type' => \App\Models\Review::class,
                'eventable_id' => $review->id,
                'region_id' => $review->spot->region_id ?? $tenerife->id,
                'payload' => [
                    'spot_id' => $review->spot_id,
                    'review_id' => $review->id,
                    'rating' => $review->overall_rating,
                    'confirms_recommendation' => 'confirms',
                    'verified_visit' => $review->is_verified,
                ],
                'created_at' => $review->created_at,
            ]);
        }

        // Review Reactions
        $reactions = \App\Models\ReviewReaction::with(['user', 'review.spot'])->get();
        foreach ($reactions as $reaction) {
            \App\Models\TimelineEvent::create([
                'user_id' => $reaction->user_id,
                'type' => 'review_reaction_created',
                'eventable_type' => \App\Models\ReviewReaction::class,
                'eventable_id' => $reaction->id,
                'region_id' => $reaction->review->spot->region_id ?? $tenerife->id,
                'payload' => [
                    'review_id' => $reaction->review_id,
                    'reaction' => $reaction->reaction,
                    'spot_name' => $reaction->review->spot->name,
                ],
                'created_at' => $reaction->created_at,
            ]);
        }

        // Tagged users (random simulation)
        $users = \App\Models\User::all();
        for ($i = 0; $i < 50; $i++) {
            $review = $reviews->random();
            $taggedUser = $users->random();

            \App\Models\TimelineEvent::create([
                'user_id' => $taggedUser->id, // Usually the one who is tagged sees it? Or the one who tagged?
                'type' => 'user_tagged_in_review',
                'eventable_type' => \App\Models\Review::class,
                'eventable_id' => $review->id,
                'region_id' => $review->spot->region_id ?? $tenerife->id,
                'payload' => [
                    'review_id' => $review->id,
                    'spot_name' => $review->spot->name,
                    'tagged_user_name' => $taggedUser->name,
                    'author_name' => $review->user->name,
                ],
                'created_at' => $review->created_at,
            ]);
        }
    }
}
