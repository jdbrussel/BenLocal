<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Spot;
use App\Models\User;
use App\Enums\ModerationStatus;
use App\Models\TimelineEvent;
use App\Models\ReviewUserTag;
use App\Models\Recommendation;
use Illuminate\Support\Facades\DB;
use App\Notifications\UserTaggedInReviewNotification;

class ReviewService
{
    public function getReviewsForSpot(Spot $spot)
    {
        return $spot->reviews()
            ->where('moderation_status', ModerationStatus::APPROVED)
            ->with(['user', 'photos', 'reactions'])
            ->latest()
            ->get();
    }

    public function createReview(User $user, Spot $spot, array $data)
    {
        return DB::transaction(function () use ($user, $spot, $data) {
            $review = Review::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'recommendation_id' => $data['recommendation_id'] ?? null,
                'overall_rating' => $data['overall_rating'],
                'rating_values' => $data['rating_values'] ?? null,
                'review_text' => $data['review_text'] ?? null,
                'original_language' => app()->getLocale(),
                'visited_at' => $data['visited_at'] ?? now(),
                'confirms_recommendation' => $data['confirms_recommendation'] ?? null,
                'moderation_status' => ModerationStatus::APPROVED, // Default to approved
                'verified_visit' => $data['verified_visit'] ?? false,
                'user_community_id' => $user->community_id,
            ]);

            $this->handleUserTags($review, $data['review_text'] ?? '');
            $this->createTimelineEvent($review);

            if ($review->recommendation_id && $review->confirms_recommendation === true) {
                $this->handleRecommendationConfirmation($review);
            }

            return $review;
        });
    }

    public function updateReview(Review $review, array $data)
    {
        return DB::transaction(function () use ($review, $data) {
            $review->update([
                'overall_rating' => $data['overall_rating'] ?? $review->overall_rating,
                'rating_values' => $data['rating_values'] ?? $review->rating_values,
                'review_text' => $data['review_text'] ?? $review->review_text,
                'confirms_recommendation' => $data['confirms_recommendation'] ?? $review->confirms_recommendation,
            ]);

            // Simple approach: clear and re-tag
            $review->tags()->delete();
            $this->handleUserTags($review, $data['review_text'] ?? ($review->getTranslation('review_text', app()->getLocale()) ?? ''));

            return $review;
        });
    }

    public function deleteReview(Review $review)
    {
        return $review->delete();
    }

    protected function handleUserTags(Review $review, $text)
    {
        if (is_array($text)) {
            $text = $text[app()->getLocale()] ?? array_values($text)[0] ?? '';
        }

        preg_match_all('/@([a-zA-Z0-9_]+)/', $text, $matches);
        $usernames = array_unique($matches[1]);

        foreach ($usernames as $username) {
            $user = User::where('name', $username)->first(); // Assuming name is used as username for now
            if ($user && $user->id !== $review->user_id) {
                ReviewUserTag::create([
                    'review_id' => $review->id,
                    'tagged_user_id' => $user->id,
                    'tagged_by_user_id' => $review->user_id,
                ]);

                // Placeholder for notification
                // $user->notify(new UserTaggedInReviewNotification($review));
            }
        }
    }

    protected function createTimelineEvent(Review $review)
    {
        TimelineEvent::create([
            'user_id' => $review->user_id,
            'type' => 'review_created',
            'eventable_type' => Review::class,
            'eventable_id' => $review->id,
            'region_id' => $review->spot->region_id,
            'payload' => [
                'spot_name' => $review->spot->getTranslation('name', app()->getLocale()),
                'rating' => $review->overall_rating,
            ],
        ]);
    }

    protected function handleRecommendationConfirmation(Review $review)
    {
        $recommendation = Recommendation::find($review->recommendation_id);
        if ($recommendation) {
            // Placeholder for "Recommendation Confirmed" notification
            // $recommendation->user->notify(new RecommendationConfirmedNotification($review, $recommendation));
        }
    }
}
