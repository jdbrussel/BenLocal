<?php

namespace App\Services;

use App\Models\Recommendation;
use App\Models\Spot;
use App\Models\User;
use App\Enums\ModerationStatus;
use App\Services\TimelineEventService;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    protected $timelineEventService;

    public function __construct(TimelineEventService $timelineEventService)
    {
        $this->timelineEventService = $timelineEventService;
    }
    public function getRecommendationsForSpot(Spot $spot)
    {
        return $spot->recommendations()
            ->where('moderation_status', ModerationStatus::APPROVED)
            ->with('user')
            ->latest()
            ->get();
    }

    public function createRecommendation(User $user, Spot $spot, array $data)
    {
        return DB::transaction(function () use ($user, $spot, $data) {
            $recommendation = Recommendation::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'region_id' => $spot->region_id,
                'community_id' => $user->community_id,
                'motivation' => $data['motivation'] ?? null,
                'hidden_gem_candidate' => $data['hidden_gem_candidate'] ?? false,
                'moderation_status' => ModerationStatus::APPROVED, // Default to approved for now as per usual starter
                'original_language' => app()->getLocale(),
            ]);

            $this->createTimelineEvent($recommendation);

            return $recommendation;
        });
    }

    public function updateRecommendation(Recommendation $recommendation, array $data)
    {
        $recommendation->update([
            'motivation' => $data['motivation'] ?? $recommendation->motivation,
            'hidden_gem_candidate' => $data['hidden_gem_candidate'] ?? $recommendation->hidden_gem_candidate,
        ]);

        return $recommendation;
    }

    public function deleteRecommendation(Recommendation $recommendation)
    {
        return $recommendation->delete();
    }

    protected function createTimelineEvent(Recommendation $recommendation)
    {
        $this->timelineEventService->createEvent(
            $recommendation->user_id,
            'recommendation_created',
            $recommendation,
            $recommendation->region_id,
            [
                'spot_name' => $recommendation->spot->getTranslation('name', app()->getLocale()),
            ]
        );
    }
}
