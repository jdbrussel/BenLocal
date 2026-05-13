<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\User;
use App\Services\TimelineEventService;
use Illuminate\Support\Facades\DB;

class ReviewReactionService
{
    protected $timelineEventService;

    public function __construct(TimelineEventService $timelineEventService)
    {
        $this->timelineEventService = $timelineEventService;
    }
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
        $this->timelineEventService->createEvent(
            $reaction->user_id,
            'review_reaction_created',
            $reaction,
            $reaction->review->spot->region_id,
            [
                'reaction' => $reaction->reaction,
                'spot_name' => $reaction->review->spot->getTranslation('name', app()->getLocale()),
            ]
        );
    }
}
