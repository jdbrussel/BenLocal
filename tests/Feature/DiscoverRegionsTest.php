<?php

namespace Tests\Feature;

use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoverRegionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_regions()
    {
        Region::factory()->create([
            'name' => ['nl' => 'Tenerife', 'en' => 'Tenerife'],
            'slug' => 'tenerife',
            'is_active' => true
        ]);

        $response = $this->getJson('/api/regions');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Tenerife');
    }

    public function test_api_handles_missing_translations()
    {
        Region::factory()->create([
            'name' => ['es' => 'Canarias'], // No NL/EN translation
            'slug' => 'canarias',
            'is_active' => true
        ]);

        $response = $this->getJson('/api/regions');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Canarias'); // Should fallback or use what's available
    }
}
