<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use App\Models\UserReputation;

/**
 * Class ReviewWeightService
 * Calculates the weight/influence of a specific review based on trust factors.
 */
class ReviewWeightService
{
    /**
     * Calculate the influence weight of a review.
     *
     * @param Review $review
     * @return float
     */
    public function calculateWeight(Review $review): float
    {
        $user = $review->user;
        $spot = $review->spot;

        // Base weight
        $weight = 1.0;

        // 1. User Reputation in the spot's region
        $reputation = UserReputation::where('user_id', $user->id)
            ->where('region_id', $spot->region_id)
            ->first();

        if ($reputation) {
            $weight += ($reputation->trust_score / 100);
        }

        // 2. Verified Visit
        if ($review->verified_visit) {
            $weight += 1.0;
        }

        // 3. Local Status
        if (in_array($review->user_region_status_at_time, ['local', 'verified_local'])) {
            $weight += 0.5;
        } elseif ($review->user_region_status_at_time === 'regular_visitor') {
            $weight += 0.3;
        }

        // 4. Historical Reliability (Visibility score of this review)
        $weight *= ($review->visibility_score / 50);

        // 5. Moderation history / Flagged count
        if ($review->flagged_count > 0) {
            $weight -= ($review->flagged_count * 0.2);
        }

        return max(0.1, $weight);
    }
}
