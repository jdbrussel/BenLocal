<?php

namespace App\Services;

use App\Models\Spot;
use App\Models\Recommendation;
use App\Models\Review;
use App\Models\UserReputation;

/**
 * Class HiddenGemService
 * Handles the logic for identifying and scoring hidden gems.
 */
class HiddenGemService
{
    /**
     * Calculate the hidden gem score for a spot.
     *
     * @param Spot $spot
     * @return float
     */
    public function calculateScore(Spot $spot): float
    {
        // 1. Recommendation count (Few is better for a hidden gem)
        $recCount = $spot->recommendations()->count();
        $recScore = 0;
        if ($recCount > 0 && $recCount < 5) {
            $recScore = 40;
        } elseif ($recCount >= 5 && $recCount < 10) {
            $recScore = 20;
        } elseif ($recCount >= 10) {
            $recScore = 0; // Not so hidden anymore
        }

        // 2. Local status strength
        $localTrustScore = $spot->recommendations()
            ->join('user_reputation', 'recommendations.user_id', '=', 'user_reputation.user_id')
            ->where('user_reputation.region_id', $spot->region_id)
            ->whereIn('user_reputation.local_status', ['local', 'verified_local'])
            ->sum('user_reputation.trust_score');

        $localValidationScore = min(30, $localTrustScore / 10);

        // 3. Tourist Saturation (Inverse)
        $touristSaturation = $this->calculateTouristSaturation($spot);
        $authenticityScore = (100 - $touristSaturation) / 100 * 20;

        // 4. Review Consistency
        $avgRating = $spot->reviews()->avg('overall_rating') ?? 0;
        $consistencyScore = $avgRating >= 4.0 ? 10 : 0;

        $totalScore = $recScore + $localValidationScore + $authenticityScore + $consistencyScore;

        return $totalScore;
    }

    public function calculateTouristSaturation(Spot $spot): float
    {
        $reviewsCount = $spot->reviews()->count();
        if ($reviewsCount === 0) return 0;

        $touristReviews = $spot->reviews()
            ->where('user_region_status_at_time', 'visitor')
            ->count();

        $localReviews = $spot->reviews()
            ->whereIn('user_region_status_at_time', ['local', 'verified_local'])
            ->count();

        if ($localReviews === 0 && $touristReviews > 0) return 100;
        if ($localReviews > 0 && $touristReviews === 0) return 0;

        return ($touristReviews / ($localReviews + $touristReviews)) * 100;
    }

    public function isHiddenGem(Spot $spot): bool
    {
        return $spot->hidden_gem_score >= 70;
    }
}
