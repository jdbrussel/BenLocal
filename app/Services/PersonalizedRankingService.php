<?php

namespace App\Services;

use App\Models\User;
use App\Models\Spot;
use App\Models\Community;
use Illuminate\Database\Eloquent\Builder;

class PersonalizedRankingService
{
    public function __construct(
        protected TrustGraphService $trustGraphService,
        protected CommunityProfileService $communityProfileService
    ) {}

    public function rankSpots(User $user, Builder $query, array $options = []): Builder
    {
        $followedUserIds = $user->follows()->pluck('followed_id')->toArray();
        $userCommunityId = $user->community_id;

        // Apply weights in the query or handle via computed scores
        // For a true database-level ranking, we'd need a complex raw SQL or use the cached scores.

        // Let's use the cached spot scores and add personalization factors
        $query->select('spots.*')
            ->selectRaw('(
                recommendation_score * 1.0 +
                review_score * 0.8 +
                local_trust_score * 1.2 +
                (CASE WHEN EXISTS (SELECT 1 FROM recommendations WHERE recommendations.spot_id = spots.id AND recommendations.user_id IN (?)) THEN 50 ELSE 0 END) +
                (CASE WHEN EXISTS (SELECT 1 FROM spot_community_profiles WHERE spot_community_profiles.spot_id = spots.id AND spot_community_profiles.community_id = ?) THEN 30 ELSE 0 END)
            ) as personalized_score', [
                implode(',', $followedUserIds) ?: '0',
                $userCommunityId ?: 0
            ]);

        // Sort by personalized score
        $query->orderByRaw('personalized_score DESC');

        return $query;
    }

    public function getSortModes(): array
    {
        return [
            'recommended_for_you',
            'hidden_gems',
            'trusted_locals',
            'trending',
            'tourist_favourites',
            'authentic_local',
            'community_match',
        ];
    }
}
