<?php

namespace Tests\Feature;

use App\Models\Follow;
use App\Models\Recommendation;
use App\Models\Region;
use App\Models\Review;
use App\Models\Spot;
use App\Models\TimelineEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase8FeedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create necessary base data
        $this->region = Region::factory()->create(['name' => 'Tenerife', 'slug' => 'tenerife']);
        $this->spot = Spot::factory()->create(['region_id' => $this->region->id]);
    }

    public function test_feed_only_shows_events()
    {
        $user = User::factory()->create();

        TimelineEvent::create([
            'user_id' => $user->id,
            'type' => 'recommendation_created',
            'eventable_type' => Recommendation::class,
            'eventable_id' => Recommendation::factory()->create(['user_id' => $user->id, 'spot_id' => $this->spot->id])->id,
            'region_id' => $this->region->id,
            'payload' => ['spot_name' => 'Test Spot']
        ]);

        $response = $this->getJson('/api/feed');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_followed_users_are_prioritized()
    {
        $user = User::factory()->create();
        $followedUser = User::factory()->create();
        $otherUser = User::factory()->create();

        Follow::create([
            'follower_id' => $user->id,
            'followed_id' => $followedUser->id
        ]);

        // Event from non-followed user (more recent)
        TimelineEvent::create([
            'user_id' => $otherUser->id,
            'type' => 'recommendation_created',
            'eventable_type' => Recommendation::class,
            'eventable_id' => Recommendation::factory()->create(['user_id' => $otherUser->id, 'spot_id' => $this->spot->id])->id,
            'region_id' => $this->region->id,
            'created_at' => now(),
            'payload' => ['spot_name' => 'Recent Spot']
        ]);

        // Event from followed user (older)
        TimelineEvent::create([
            'user_id' => $followedUser->id,
            'type' => 'recommendation_created',
            'eventable_type' => Recommendation::class,
            'eventable_id' => Recommendation::factory()->create(['user_id' => $followedUser->id, 'spot_id' => $this->spot->id])->id,
            'region_id' => $this->region->id,
            'created_at' => now()->subDay(),
            'payload' => ['spot_name' => 'Older Spot']
        ]);

        $response = $this->actingAs($user)->getJson('/api/feed');

        $response->assertStatus(200);
        // Followed user's event should be first due to ranking score (100 vs 0)
        $this->assertEquals($followedUser->id, $response->json('data.0.user.id'));
    }

    public function test_region_filtering_works()
    {
        $region2 = Region::factory()->create(['name' => 'Gran Canaria']);
        $spot2 = Spot::factory()->create(['region_id' => $region2->id]);
        $user = User::factory()->create();

        // Event in region 1
        TimelineEvent::create([
            'user_id' => $user->id,
            'type' => 'recommendation_created',
            'eventable_type' => Recommendation::class,
            'eventable_id' => Recommendation::factory()->create(['user_id' => $user->id, 'spot_id' => $this->spot->id])->id,
            'region_id' => $this->region->id,
            'payload' => ['spot_name' => 'Tenerife Spot']
        ]);

        // Event in region 2
        TimelineEvent::create([
            'user_id' => $user->id,
            'type' => 'recommendation_created',
            'eventable_type' => Recommendation::class,
            'eventable_id' => Recommendation::factory()->create(['user_id' => $user->id, 'spot_id' => $spot2->id])->id,
            'region_id' => $region2->id,
            'payload' => ['spot_name' => 'GC Spot']
        ]);

        $response = $this->getJson('/api/feed?region_id=' . $this->region->id);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $this->assertEquals($this->region->id, $response->json('data.0.region.id'));
    }

    public function test_guest_feed_fallback_works()
    {
        $user = User::factory()->create();

        TimelineEvent::create([
            'user_id' => $user->id,
            'type' => 'recommendation_created',
            'eventable_type' => Recommendation::class,
            'eventable_id' => Recommendation::factory()->create(['user_id' => $user->id, 'spot_id' => $this->spot->id])->id,
            'region_id' => $this->region->id,
            'payload' => ['spot_name' => 'Test Spot']
        ]);

        // No actingAs
        $response = $this->getJson('/api/feed');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_user_activity_endpoint_works()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        TimelineEvent::create([
            'user_id' => $user->id,
            'type' => 'recommendation_created',
            'eventable_type' => Recommendation::class,
            'eventable_id' => Recommendation::factory()->create(['user_id' => $user->id, 'spot_id' => $this->spot->id])->id,
            'region_id' => $this->region->id,
            'payload' => ['spot_name' => 'User Spot']
        ]);

        TimelineEvent::create([
            'user_id' => $otherUser->id,
            'type' => 'recommendation_created',
            'eventable_type' => Recommendation::class,
            'eventable_id' => Recommendation::factory()->create(['user_id' => $otherUser->id, 'spot_id' => $this->spot->id])->id,
            'region_id' => $this->region->id,
            'payload' => ['spot_name' => 'Other Spot']
        ]);

        $response = $this->actingAs($user)->getJson("/api/users/{$user->id}/activity");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $this->assertEquals($user->id, $response->json('data.0.user.id'));
    }
}
