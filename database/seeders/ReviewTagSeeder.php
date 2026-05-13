<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\ReviewUserTag;
use App\Models\User;
use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;

class ReviewTagSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = Review::with('spot')->get();
        $users = User::all();

        foreach ($reviews->random(intval($reviews->count() * 0.15)) as $review) {
            $tagCount = fake()->numberBetween(1, 2);
            $taggedUsers = $users->where('id', '!=', $review->user_id)->random($tagCount);

            foreach ($taggedUsers as $taggedUser) {
                $tag = ReviewUserTag::create([
                    'review_id' => $review->id,
                    'tagged_user_id' => $taggedUser->id,
                    'tagged_by_user_id' => $review->user_id,
                ]);

                // Timeline Event
                TimelineEvent::create([
                    'user_id' => $review->user_id,
                    'type' => 'user_tagged_in_review',
                    'eventable_type' => ReviewUserTag::class,
                    'eventable_id' => $tag->id,
                    'region_id' => $review->spot->region_id,
                    'payload' => [
                        'spot_name' => $review->spot->name,
                        'tagged_user_name' => $taggedUser->name
                    ],
                ]);
            }
        }
    }
}
