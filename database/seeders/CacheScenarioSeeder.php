<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Region;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class CacheScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding specific data for cache testing scenarios...');

        $region = Region::where('slug', 'tenerife')->first();
        if (!$region) return;

        // 1. Spot Detail Cache Scenario
        $cachedSpot = Spot::factory()->create([
            'name' => ['en' => 'The Cache Test Spot'],
            'region_id' => $region->id,
        ]);
        $this->command->info("Created spot '{$cachedSpot->id}' for detail caching tests.");

        // 2. Personalized Feed Cache Scenario
        $testUser = User::factory()->create([
            'name' => 'Cache Test User',
            'email' => 'cache-test@example.com',
        ]);
        $this->command->info("Created user '{$testUser->id}' for feed caching tests.");

        // 3. Category Specs Cache Scenario
        $categories = Category::all();
        $this->command->info("Ensured {$categories->count()} categories exist for specs caching.");

        $this->command->info('Pre-warming cache is NOT recommended during seeding; it should happen on first request.');
    }
}
