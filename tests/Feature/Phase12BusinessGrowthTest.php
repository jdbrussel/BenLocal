<?php

namespace Tests\Feature;

use App\Models\Spot;
use App\Models\User;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase12BusinessGrowthTest extends TestCase
{
    use RefreshDatabase;

    protected $subscriptionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = new SubscriptionService();
    }

    /** @test */
    public function it_tracks_spot_views()
    {
        $spot = Spot::factory()->create();

        $this->getJson("/api/spots/{$spot->slug}");

        $this->assertDatabaseHas('spot_analytics', [
            'spot_id' => $spot->id,
            'metric_type' => 'view',
        ]);
    }

    /** @test */
    public function free_plan_cannot_access_analytics()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->create();
        // No subscription = free plan

        $response = $this->actingAs($user)->getJson("/api/owner/spots/{$spot->id}/analytics");

        $response->assertStatus(403);
    }

    /** @test */
    public function pro_plan_can_access_analytics()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->create();
        Subscription::create([
            'spot_id' => $spot->id,
            'user_id' => $user->id,
            'plan_type' => 'pro',
            'stripe_status' => 'active',
        ]);

        $response = $this->actingAs($user)->getJson("/api/owner/spots/{$spot->id}/analytics");

        $response->assertStatus(200);
        $response->assertJson(['can_access' => true]);
    }

    /** @test */
    public function owner_can_create_offer_on_pro_plan()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->create();
        Subscription::create([
            'spot_id' => $spot->id,
            'user_id' => $user->id,
            'plan_type' => 'pro',
            'stripe_status' => 'active',
        ]);

        $response = $this->actingAs($user)->postJson("/api/owner/spots/{$spot->id}/offers", [
            'title' => ['en' => 'Special Discount', 'es' => 'Descuento Especial'],
            'description' => ['en' => 'Get 20% off', 'es' => 'Obtén 20% de descuento'],
            'coupon_code' => 'LOCAL20',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('offers', [
            'spot_id' => $spot->id,
            'coupon_code' => 'LOCAL20',
        ]);
    }

    /** @test */
    public function spot_detail_includes_active_offers_and_events()
    {
        $spot = Spot::factory()->create();
        $spot->offers()->create([
            'title' => ['en' => 'Offer 1'],
            'is_active' => true,
        ]);
        $spot->events()->create([
            'title' => ['en' => 'Event 1'],
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDays(2),
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/spots/{$spot->slug}");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data.offers');
        $response->assertJsonCount(1, 'data.events');
    }
}
