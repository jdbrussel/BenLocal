<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\User;
use App\Models\TimelineEvent;
use Illuminate\Support\Facades\DB;

class ReviewReactionService
{
    public function react(User $user, Review $review, array $data)
    {
        return DB::transaction(function () use ($user, $review, $data) {
            $reaction = ReviewReaction::updateOrCreate(
                ['user_id' => $user->id, 'review_id' => $review->id],
                [
                    'reaction' => $data['reaction'],
                    'weight' => $user->trust_penalty_score > 0 ? 0.5 : 1.0, // Example weight logic
                ]
            );

            if ($reaction->wasRecentlyCreated) {
                $this->createTimelineEvent($reaction);
                // Placeholder for notification
                // $review->user->notify(new ReviewValidatedNotification($reaction));
            }

            return $reaction;
        });
    }

    public function removeReaction(User $user, Review $review)
    {
        return ReviewReaction::where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->delete();
    }

    protected function createTimelineEvent(ReviewReaction $reaction)
    {
        TimelineEvent::create([
            'user_id' => $reaction->user_id,
            'type' => 'review_reaction_created',
            'eventable_type' => ReviewReaction::class,
            'eventable_id' => $reaction->id,
            'region_id' => $reaction->review->spot->region_id,
            'payload' => [
                'reaction' => $reaction->reaction,
                'spot_name' => $reaction->review->spot->getTranslation('name', app()->getLocale()),
            ],
        ]);
    }
}
