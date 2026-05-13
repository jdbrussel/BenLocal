<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserReputation;
use App\Models\Recommendation;
use App\Models\Review;
use App\Models\SpotVisit;
use App\Models\UserRegionStatus;
use App\Enums\UserRegionStatus as UserRegionStatusEnum;
use Illuminate\Support\Facades\DB;

/**
 * Class UserReputationService
 * Handles the calculation of user reputation based on various trust signals.
 */
class UserReputationService
{
    /**
     * Recalculate and update reputation for a user in a specific context.
     * Context can include region_id, sector_id, category_id, or community_id.
     *
     * @param User $user
     * @param array $context
     * @return UserReputation
     */
    public function recalculateReputation(User $user, array $context = [])
    {
        $query = UserReputation::where('user_id', $user->id);

        foreach (['region_id', 'sector_id', 'category_id', 'community_id'] as $key) {
            if (isset($context[$key])) {
                $query->where($key, $context[$key]);
            } else {
                $query->whereNull($key);
            }
        }

        $reputation = $query->firstOrNew([
            'user_id' => $user->id,
            'region_id' => $context['region_id'] ?? null,
            'sector_id' => $context['sector_id'] ?? null,
            'category_id' => $context['category_id'] ?? null,
            'community_id' => $context['community_id'] ?? null,
        ]);

        $metrics = $this->calculateMetrics($user, $context);

        $reputation->fill($metrics);
        $reputation->save();

        return $reputation;
    }

    /**
     * Internal calculation logic for reputation metrics.
     *
     * @param User $user
     * @param array $context
     * @return array
     */
    protected function calculateMetrics(User $user, array $context): array
    {
        $regionId = $context['region_id'] ?? null;

        // 1. Recommendations confirmed by reviews
        $recs = Recommendation::where('user_id', $user->id)
            ->when($regionId, fn($q) => $q->where('region_id', $regionId))
            ->withCount(['reviews as confirmed_count' => function($q) {
                $q->where('confirms_recommendation', true);
            }])
            ->get();

        $recommendationCount = $recs->count();
        $confirmedScore = $recs->sum('confirmed_count') * 2.0;

        // 2. Review validation quality (Average visibility score of their reviews)
        $reviewMetrics = Review::where('user_id', $user->id)
            ->when($regionId, function($q) use ($regionId) {
                $q->whereHas('spot', fn($sq) => $sq->where('region_id', $regionId));
            })
            ->selectRaw('COUNT(*) as count, AVG(visibility_score) as avg_visibility, SUM(verified_visit) as verified_count')
            ->first();

        $reviewScore = ($reviewMetrics->avg_visibility ?? 0) * 10;
        $verifiedVisits = (int) $reviewMetrics->verified_count;

        // 3. Hidden gems discovered
        $hiddenGemScore = Recommendation::where('user_id', $user->id)
            ->where('hidden_gem_candidate', true)
            ->when($regionId, fn($q) => $q->where('region_id', $regionId))
            ->whereHas('spot', fn($q) => $q->where('hidden_gem_score', '>', 50))
            ->count() * 5.0;

        // 4. Local status
        $localStatus = 'visitor';
        if ($regionId) {
            $status = UserRegionStatus::where('user_id', $user->id)
                ->where('region_id', $regionId)
                ->first();
            $localStatus = $status ? $status->status->value : 'visitor';
        }

        $localMultiplier = match($localStatus) {
            'local', 'verified_local' => 1.5,
            'regular_visitor' => 1.2,
            default => 1.0,
        };

        // 5. Follower count
        $followerCount = $user->followers()->count();

        // 6. Trust Penalties
        $penalty = $user->trust_penalty_score ?? 0;

        // Base Trust Score Calculation
        $trustScore = (
            ($confirmedScore * 2) +
            ($reviewScore) +
            ($hiddenGemScore * 3) +
            ($verifiedVisits * 5) +
            (log($followerCount + 1) * 10) +
            10 // Base trust
        ) * $localMultiplier;

        $trustScore = max(0, $trustScore - $penalty);

        return [
            'local_status' => $localStatus,
            'recommendation_count' => $recommendationCount,
            'confirmed_recommendation_score' => $confirmedScore,
            'review_score' => $reviewScore,
            'follower_count' => $followerCount,
            'hidden_gem_score' => $hiddenGemScore,
            'trust_score' => $trustScore,
        ];
    }
}
