<?php

namespace Tests\Feature\Api;

use App\Models\Sector;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $sector = Sector::create(['name' => ['en' => 'Food'], 'slug' => 'food', 'is_active' => true]);
        Category::create([
            'sector_id' => $sector->id,
            'name' => ['en' => 'Restaurants'],
            'slug' => 'restaurants',
            'is_active' => true
        ]);
    }

    public function test_it_can_list_sectors()
    {
        $response = $this->getJson('/api/sectors');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'food');
    }

    public function test_it_can_list_categories()
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'restaurants');
    }

    public function test_it_can_show_category()
    {
        $category = Category::where('slug', 'restaurants')->first();
        $response = $this->getJson("/api/categories/{$category->slug}");

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'restaurants');
    }
}
