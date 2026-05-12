<?php

namespace Tests\Feature\Api;

use App\Models\Spot;
use App\Models\Region;
use App\Models\Category;
use App\Models\Sector;
use App\Enums\SpotLifecycleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $sector = Sector::create(['name' => ['en' => 'Food'], 'slug' => 'food']);
        $category = Category::create([
            'sector_id' => $sector->id,
            'name' => ['en' => 'Restaurants'],
            'slug' => 'restaurants'
        ]);
        $region = Region::create(['name' => ['en' => 'Tenerife'], 'slug' => 'tenerife']);

        Spot::factory()->count(5)->create([
            'sector_id' => $sector->id,
            'category_id' => $category->id,
            'region_id' => $region->id,
            'lifecycle_status' => SpotLifecycleStatus::ACTIVE,
        ]);
    }

    public function test_it_can_list_spots()
    {
        $response = $this->getJson('/api/discover');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_it_can_filter_by_region()
    {
        $otherRegion = Region::create(['name' => ['en' => 'Gran Canaria'], 'slug' => 'gran-canaria']);
        Spot::factory()->create([
            'region_id' => $otherRegion->id,
            'lifecycle_status' => SpotLifecycleStatus::ACTIVE,
        ]);

        $response = $this->getJson('/api/discover?region=tenerife');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_it_can_filter_by_category()
    {
        $response = $this->getJson('/api/discover?category=restaurants');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_it_can_search_by_name()
    {
        Spot::factory()->create([
            'name' => ['en' => 'Unique Spot Name'],
            'lifecycle_status' => SpotLifecycleStatus::ACTIVE,
        ]);

        $response = $this->getJson('/api/discover?q=Unique');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Unique Spot Name');
    }
}
