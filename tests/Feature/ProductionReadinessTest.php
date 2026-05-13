<?php

namespace Tests\Feature;

use App\Models\Spot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_rate_limiting_works()
    {
        $user = User::factory()->create();

        // We set it to 60 per minute in bootstrap/app.php
        for ($i = 0; $i < 60; $i++) {
            $response = $this->getJson('/api/health');
            $response->assertStatus(200);
        }

        $response = $this->getJson('/api/health');
        $response->assertStatus(429);
    }

    public function test_discovery_caching_works()
    {
        Cache::shouldReceive('tags')
            ->with(['spots', 'discovery'])
            ->andReturnSelf()
            ->shouldReceive('remember')
            ->once()
            ->andReturn(collect());

        $this->getJson('/api/discover');
    }

    public function test_cache_clear_command()
    {
        Cache::fake();

        $this->artisan('benlocal:clear-stale-cache')
            ->expectsOutput('Clearing stale cache...')
            ->assertExitCode(0);
    }

    public function test_health_check_returns_correct_structure()
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'services' => [
                    'database',
                    'cache',
                    'redis'
                ]
            ]);
    }
}
