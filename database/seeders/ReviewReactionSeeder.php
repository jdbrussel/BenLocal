<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\User;
use App\Models\TimelineEvent;
use App\Enums\ReviewReactionType;
use Illuminate\Database\Seeder;

class ReviewReactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $reviews = Review::with('spot')->get();

        $count = fake()->numberBetween(2000, 4000);
        $reactionsCreated = 0;

        while ($reactionsCreated < $count) {
            $review = $reviews->random();
            $user = $users->random();

            // User cannot react to own review
            if ($user->id === $review->user_id) continue;

            // One reaction per user per review
            if (ReviewReaction::where('user_id', $user->id)->where('review_id', $review->id)->exists()) {
                continue;
            }

            // Distribution: 65% agree, 25% partly, 10% disagree
            $type = fake()->randomElement([
                ReviewReactionType::AGREE, ReviewReactionType::AGREE, ReviewReactionType::AGREE,
                ReviewReactionType::AGREE, ReviewReactionType::AGREE, ReviewReactionType::AGREE, ReviewReactionType::AGREE,
                ReviewReactionType::PARTLY, ReviewReactionType::PARTLY, ReviewReactionType::PARTLY,
                ReviewReactionType::DISAGREE
            ]);

            $reaction = ReviewReaction::create([
                'user_id' => $user->id,
                'review_id' => $review->id,
                'reaction' => $type,
                'weight' => 1.0,
            ]);

            // Timeline Event (limited to avoid too many events)
            if (fake()->boolean(20)) {
                TimelineEvent::create([
                    'user_id' => $user->id,
                    'type' => 'review_reaction_created',
                    'eventable_type' => ReviewReaction::class,
                    'eventable_id' => $reaction->id,
                    'region_id' => $review->spot->region_id,
                    'payload' => [
                        'spot_name' => $review->spot->name,
                        'reaction' => $type->value
                    ],
                ]);
            }

            $reactionsCreated++;
        }
    }
}
