<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\Review;
use App\Models\Spot;
use App\Models\User;
use App\Models\UserRegionStatus;
use App\Models\Sector;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class Phase6InteractionsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $local;
    protected $spot;
    protected $region;

    protected function setUp(): void
    {
        parent::setUp();

        $this->region = Region::create(['name' => ['en' => 'Tenerife'], 'slug' => 'tenerife']);

        $sector = Sector::create(['name' => ['en' => 'Food'], 'slug' => 'food']);
        $category = Category::create(['name' => ['en' => 'Restaurant'], 'slug' => 'restaurant', 'sector_id' => $sector->id]);

        $this->spot = Spot::create([
            'name' => ['en' => 'Nice Restaurant'],
            'slug' => 'nice-restaurant',
            'region_id' => $this->region->id,
            'sector_id' => $sector->id,
            'category_id' => $category->id,
            'lifecycle_status' => 'active',
        ]);

        $this->user = User::factory()->create();
        $this->local = User::factory()->create(['name' => 'local_user']);

        UserRegionStatus::create([
            'user_id' => $this->local->id,
            'region_id' => $this->region->id,
            'status' => 'local',
        ]);
    }

    public function test_local_can_recommend()
    {
        Sanctum::actingAs($this->local);

        $response = $this->postJson("/api/spots/{$this->spot->id}/recommendations", [
            'motivation' => 'Great food!',
            'hidden_gem_candidate' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('recommendations', [
            'user_id' => $this->local->id,
            'spot_id' => $this->spot->id,
        ]);
    }

    public function test_tourist_cannot_recommend()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/spots/{$this->spot->id}/recommendations", [
            'motivation' => 'Great food!',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_review()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/spots/{$this->spot->id}/reviews", [
            'overall_rating' => 5,
            'review_text' => 'Amazing place!',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'spot_id' => $this->spot->id,
            'overall_rating' => 5,
        ]);
    }

    public function test_user_can_react_to_review()
    {
        $review = Review::create([
            'user_id' => $this->local->id,
            'spot_id' => $this->spot->id,
            'overall_rating' => 4,
            'moderation_status' => 'approved',
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/reviews/{$review->id}/reaction", [
            'reaction' => 'agree',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('review_reactions', [
            'user_id' => $this->user->id,
            'review_id' => $review->id,
            'reaction' => 'agree',
        ]);
    }

    public function test_user_cannot_react_to_own_review()
    {
        $review = Review::create([
            'user_id' => $this->user->id,
            'spot_id' => $this->spot->id,
            'overall_rating' => 4,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/reviews/{$review->id}/reaction", [
            'reaction' => 'agree',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_tagging_in_review()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/spots/{$this->spot->id}/reviews", [
            'overall_rating' => 5,
            'review_text' => "Check this out @local_user",
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('review_user_tags', [
            'tagged_user_id' => $this->local->id,
            'tagged_by_user_id' => $this->user->id,
        ]);
    }
}
