<?php

namespace App\Jobs;

use App\Models\Spot;
use App\Services\SpotRankingService;
use App\Services\CommunityProfileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateSpotScoresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Spot $spot) {}

    public function handle(SpotRankingService $rankingService, CommunityProfileService $communityService)
    {
        // 1. Update dynamic community profile
        $communityService->updateSpotProfile($this->spot);

        // 2. Recalculate all scores
        $rankingService->recalculateScores($this->spot);
    }
}
