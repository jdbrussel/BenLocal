<?php

namespace App\Services;

use App\Models\Recommendation;
use App\Models\UserReputation;
use App\Models\Review;

/**
 * Class RecommendationScoreService
 * Calculates trust and visibility scores for recommendations.
 */
class RecommendationScoreService
{
    /**
     * Calculate scores for a recommendation.
     *
     * @param Recommendation $rec
     * @return array Contains 'trust_score' and 'visibility_score'
     */
    public function calculateScores(Recommendation $rec): array
    {
        $user = $rec->user;
        $spot = $rec->spot;

        // 1. Local Status & User Reputation
        $reputation = UserReputation::where('user_id', $user->id)
            ->where('region_id', $rec->region_id)
            ->first();

        $baseTrust = $reputation ? $reputation->trust_score : 10.0;

        // 2. Recommendation Validation (Confirmation by others)
        $confirmations = Review::where('recommendation_id', $rec->id)
            ->where('confirms_recommendation', true)
            ->count();

        $validationBonus = $confirmations * 15.0;

        // 3. Verified visits by the recommender
        $verifiedVisits = Review::where('user_id', $user->id)
            ->where('spot_id', $spot->id)
            ->where('verified_visit', true)
            ->count();

        $visitBonus = $verifiedVisits * 20.0;

        // 4. Recency (decay over 1 year)
        $daysOld = $rec->created_at->diffInDays(now());
        $recencyMultiplier = max(0.5, 1 - ($daysOld / 365));

        // 5. Community Alignment
        $communityBonus = 0;
        if ($rec->community_id && $rec->community_id === $user->community_id) {
            $communityBonus = 10.0;
        }

        $trustScore = ($baseTrust + $validationBonus + $visitBonus + $communityBonus) * $recencyMultiplier;

        // Visibility Score
        $visibilityScore = $trustScore * ($rec->confidence_score / 100);

        // Penalize if moderation state is not approved
        if ($rec->moderation_status && $rec->moderation_status->value !== 'approved') {
            $trustScore *= 0.1;
            $visibilityScore *= 0.1;
        }

        return [
            'trust_score' => $trustScore,
            'visibility_score' => $visibilityScore,
        ];
    }
}
