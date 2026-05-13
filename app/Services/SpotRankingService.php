<?php

namespace App\Services;

use App\Models\Spot;
use App\Models\Recommendation;
use App\Models\Review;

/**
 * Class SpotRankingService
 * Aggregates all trust and ranking scores for a spot.
 */
class SpotRankingService
{
    /**
     * @param RecommendationScoreService $recommendationScoreService
     * @param ReviewWeightService $reviewWeightService
     * @param HiddenGemService $hiddenGemService
     */
    public function __construct(
        protected RecommendationScoreService $recommendationScoreService,
        protected ReviewWeightService $reviewWeightService,
        protected HiddenGemService $hiddenGemService
    ) {}

    /**
     * Recalculate all scores for a spot.
     *
     * @param Spot $spot
     * @return void
     */
    public function recalculateScores(Spot $spot)
    {
        // 1. Recommendation Score
        $recs = $spot->recommendations;
        $totalRecScore = 0;
        foreach ($recs as $rec) {
            $scores = $this->recommendationScoreService->calculateScores($rec);
            $rec->update([
                'trust_score' => $scores['trust_score'],
                'visibility_score' => $scores['visibility_score'],
            ]);
            $totalRecScore += $scores['trust_score'];
        }
        $spot->recommendation_score = $totalRecScore;

        // 2. Review Score
        $reviews = $spot->reviews;
        $weightedRatingSum = 0;
        $totalWeight = 0;
        foreach ($reviews as $review) {
            $weight = $this->reviewWeightService->calculateWeight($review);
            $review->update(['weight' => $weight]);

            $weightedRatingSum += ($review->overall_rating * $weight);
            $totalWeight += $weight;
        }
        $spot->review_score = $totalWeight > 0 ? ($weightedRatingSum / $totalWeight) * 20 : 0; // Scale to 100

        // 3. Hidden Gem Score
        $spot->hidden_gem_score = $this->hiddenGemService->calculateScore($spot);

        // 4. Tourist Saturation
        $spot->tourist_saturation_score = $this->hiddenGemService->calculateTouristSaturation($spot);

        // 5. Local Trust Score
        $spot->local_trust_score = $spot->recommendations()
            ->join('user_reputation', 'recommendations.user_id', '=', 'user_reputation.user_id')
            ->where('user_reputation.region_id', $spot->region_id)
            ->sum('user_reputation.trust_score');

        // 6. Dynamic Badges
        $this->updateBadges($spot);

        $spot->save();
    }

    protected function updateBadges(Spot $spot)
    {
        // Hidden Gem Badge
        $hiddenGemBadge = \App\Models\SpotBadge::where('key', 'hidden-gem')->first();
        if ($hiddenGemBadge) {
            if ($spot->hidden_gem_score >= 70) {
                $spot->badges()->syncWithoutDetaching([$hiddenGemBadge->id => ['auto_assigned' => true]]);
            } else {
                $spot->badges()->detach($hiddenGemBadge->id);
            }
        }

        // Trusted by Locals Badge
        $trustedLocalBadge = \App\Models\SpotBadge::where('key', 'trusted-local')->first();
        if ($trustedLocalBadge) {
            if ($spot->local_trust_score >= 100) {
                $spot->badges()->syncWithoutDetaching([$trustedLocalBadge->id => ['auto_assigned' => true]]);
            } else {
                $spot->badges()->detach($trustedLocalBadge->id);
            }
        }
    }
}
