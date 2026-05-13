<?php

namespace App\Services;

use App\Models\Spot;
use App\Models\Review;
use App\Models\Community;
use App\Models\SpotCommunityProfile;
use Illuminate\Support\Facades\DB;

/**
 * Class CommunityProfileService
 * Manages the community distribution profile for each spot.
 */
class CommunityProfileService
{
    /**
     * Update the dynamic community profile for a spot.
     *
     * @param Spot $spot
     * @return void
     */
    public function updateSpotProfile(Spot $spot)
    {
        // Get community distribution from reviews
        $reviewCommunities = Review::where('spot_id', $spot->id)
            ->whereNotNull('user_community_id')
            ->select('user_community_id', DB::raw('count(*) as total'))
            ->groupBy('user_community_id')
            ->get();

        // Get community distribution from recommendations
        $recCommunities = $spot->recommendations()
            ->whereNotNull('community_id')
            ->select('community_id', DB::raw('count(*) as total'))
            ->groupBy('community_id')
            ->get();

        $totals = [];
        $grandTotal = 0;

        foreach ($reviewCommunities as $rc) {
            $totals[$rc->user_community_id] = ($totals[$rc->user_community_id] ?? 0) + ($rc->total * 1.0);
            $grandTotal += ($rc->total * 1.0);
        }

        foreach ($recCommunities as $rc) {
            $totals[$rc->community_id] = ($totals[$rc->community_id] ?? 0) + ($rc->total * 2.0); // Recs count more
            $grandTotal += ($rc->total * 2.0);
        }

        if ($grandTotal === 0) return;

        // Clear old profiles
        $spot->communityProfiles()->delete();

        foreach ($totals as $communityId => $weightedTotal) {
            $percentage = ($weightedTotal / $grandTotal) * 100;

            SpotCommunityProfile::create([
                'spot_id' => $spot->id,
                'community_id' => $communityId,
                'percentage' => $percentage,
                'confidence_score' => min(100, $weightedTotal * 10),
                'source' => 'dynamic_aggregation'
            ]);
        }
    }

    public function getMatchScore(Spot $spot, Community $community): float
    {
        $profile = $spot->communityProfiles()->where('community_id', $community->id)->first();
        return $profile ? (float)$profile->percentage : 0.0;
    }
}
