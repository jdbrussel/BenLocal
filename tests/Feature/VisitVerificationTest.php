<?php

namespace Tests\Feature;

use App\Models\Spot;
use App\Models\User;
use App\Services\VisitVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected VisitVerificationService $verificationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->verificationService = new VisitVerificationService();
    }

    public function test_gps_distance_validation()
    {
        $spot = Spot::factory()->create([
            'latitude' => 28.1281,
            'longitude' => -15.4468,
        ]);

        // Very close (approx 10m)
        $result = $this->verificationService->verifyGps($spot, 28.1282, -15.4469);
        $this->assertEquals(1.0, $result['score']);
        $this->assertFalse($result['is_suspicious']);

        // Medium distance (approx 200m)
        $result = $this->verificationService->verifyGps($spot, 28.1300, -15.4468);
        $this->assertEquals(0.8, $result['score']);
        $this->assertFalse($result['is_suspicious']);

        // Far away (approx 1km)
        $result = $this->verificationService->verifyGps($spot, 28.1400, -15.4468);
        $this->assertEquals(0.1, $result['score']);
        $this->assertTrue($result['is_suspicious']);
    }

    public function test_qr_check_in_works()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->create([
            'qr_token' => 'VALID_TOKEN',
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/spots/{$spot->id}/qr-check-in", [
                'token' => 'VALID_TOKEN',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('is_verified', true);

        $this->assertDatabaseHas('spot_visits', [
            'user_id' => $user->id,
            'spot_id' => $spot->id,
            'visit_source' => 'qr',
            'verification_score' => 1.0,
        ]);
    }

    public function test_invalid_qr_check_in_fails()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->create([
            'qr_token' => 'VALID_TOKEN',
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/spots/{$spot->id}/qr-check-in", [
                'token' => 'WRONG_TOKEN',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('is_verified', false);
    }

    public function test_verified_visit_links_to_recent_review()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->create([
            'latitude' => 28.1281,
            'longitude' => -15.4468,
        ]);

        // Create a review first
        $review = $user->reviews()->create([
            'spot_id' => $spot->id,
            'overall_rating' => 5,
            'review_text' => ['en' => 'Great place!'],
        ]);

        // Perform GPS check-in (at the spot)
        $response = $this->actingAs($user)
            ->postJson("/api/spots/{$spot->id}/check-in", [
                'latitude' => 28.1281,
                'longitude' => -15.4468,
            ]);

        $response->assertStatus(201);

        // Check if review was updated
        $review->refresh();
        $this->assertTrue($review->verified_visit);
        $this->assertNotNull($review->spot_visit_id);
    }
}
