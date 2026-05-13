<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Spot;
use App\Models\Recommendation;
use App\Models\Review;
use App\Models\Region;
use App\Models\Sector;
use App\Models\Category;
use App\Models\UserRegionStatus;
use App\Enums\UserRegionStatus as UserRegionStatusEnum;
use App\Services\UserReputationService;
use App\Services\SpotRankingService;
use App\Services\RecommendationScoreService;
use App\Services\ReviewWeightService;
use App\Services\HiddenGemService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase7RankingTest extends TestCase
{
    use RefreshDatabase;

    public function test_local_recommendations_weigh_more()
    {
        $region = Region::factory()->create();
        $sector = Sector::factory()->create();
        $category = Category::factory()->create(['sector_id' => $sector->id]);
        $spot = Spot::factory()->create([
            'region_id' => $region->id,
            'sector_id' => $sector->id,
            'category_id' => $category->id
        ]);

        $resident = User::factory()->create();
        UserRegionStatus::factory()->create([
            'user_id' => $resident->id,
            'region_id' => $region->id,
            'status' => UserRegionStatusEnum::LOCAL
        ]);

        $visitor = User::factory()->create();
        UserRegionStatus::factory()->create([
            'user_id' => $visitor->id,
            'region_id' => $region->id,
            'status' => UserRegionStatusEnum::VISITOR
        ]);

        // Recalculate reputation to get the multipliers
        app(UserReputationService::class)->recalculateReputation($resident, ['region_id' => $region->id]);
        app(UserReputationService::class)->recalculateReputation($visitor, ['region_id' => $region->id]);

        $rec1 = Recommendation::factory()->create([
            'user_id' => $resident->id,
            'spot_id' => $spot->id,
            'region_id' => $region->id,
            'confidence_score' => 100
        ]);

        $rec2 = Recommendation::factory()->create([
            'user_id' => $visitor->id,
            'spot_id' => $spot->id,
            'region_id' => $region->id,
            'confidence_score' => 100
        ]);

        $scoreService = app(RecommendationScoreService::class);
        $scores1 = $scoreService->calculateScores($rec1);
        $scores2 = $scoreService->calculateScores($rec2);

        // dd($scores1, $scores2, \App\Models\UserReputation::all()->toArray());

        $this->assertGreaterThan($scores2['trust_score'], $scores1['trust_score']);
    }

    public function test_hidden_gem_detection()
    {
        $region = Region::factory()->create();
        $spot = Spot::factory()->create(['region_id' => $region->id]);

        // Add 2 local recommendations
        $locals = User::factory()->count(2)->create();
        foreach ($locals as $local) {
             UserRegionStatus::factory()->create([
                'user_id' => $local->id,
                'region_id' => $region->id,
                'status' => UserRegionStatusEnum::LOCAL
            ]);
            app(UserReputationService::class)->recalculateReputation($local, ['region_id' => $region->id]);

            Recommendation::factory()->create([
                'user_id' => $local->id,
                'spot_id' => $spot->id,
                'region_id' => $region->id
            ]);
        }

        $gemService = app(HiddenGemService::class);
        $score = $gemService->calculateScore($spot);

        $this->assertGreaterThan(50, $score); // Should have a decent score
    }

    public function test_tourist_saturation_lowers_authenticity()
    {
         $region = Region::factory()->create();
         $spot = Spot::factory()->create(['region_id' => $region->id]);

         // 80% tourist reviews
         Review::factory()->count(8)->create([
             'spot_id' => $spot->id,
             'user_region_status_at_time' => 'visitor',
             'visibility_score' => 50
         ]);
         Review::factory()->count(2)->create([
             'spot_id' => $spot->id,
             'user_region_status_at_time' => 'local',
             'visibility_score' => 50
         ]);

         $gemService = app(HiddenGemService::class);
         $saturation = $gemService->calculateTouristSaturation($spot);

         $this->assertEquals(80.0, $saturation);
    }
}
