<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use App\Models\Category;
use App\Models\Sector;
use App\Models\Spot;
use App\Models\Campaign;
use App\Models\ClaimToken;
use App\Enums\SpotLifecycleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class FrontendIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create base data for frontend pages
        $this->sector = Sector::create(['name' => ['en' => 'Food'], 'slug' => 'food']);
        $this->category = Category::create([
            'sector_id' => $this->sector->id,
            'name' => ['en' => 'Restaurants'],
            'slug' => 'restaurants'
        ]);
        $this->region = Region::create(['name' => ['en' => 'Tenerife'], 'slug' => 'tenerife']);

        $this->spot = Spot::factory()->create([
            'sector_id' => $this->sector->id,
            'category_id' => $this->category->id,
            'region_id' => $this->region->id,
            'lifecycle_status' => SpotLifecycleStatus::ACTIVE,
            'name' => ['en' => 'Test Restaurant'],
            'slug' => 'test-restaurant'
        ]);
    }

    public function test_discover_page_renders()
    {
        $response = $this->get(route('discover'));
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Discover')
            );
    }

    public function test_spot_detail_page_renders()
    {
        $response = $this->get(route('spot.show', ['slug' => 'test-restaurant']));
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('SpotDetail')
                ->where('slug', 'test-restaurant')
            );
    }

    public function test_feed_page_renders()
    {
        $response = $this->get(route('feed'));
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Feed')
            );
    }

    public function test_search_page_renders()
    {
        $response = $this->get(route('search'));
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Search')
            );
    }

    public function test_profile_page_renders_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('profile'));
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Profile')
            );
    }

    public function test_settings_page_renders()
    {
        $response = $this->get(route('settings'));
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Settings')
            );
    }

    public function test_onboarding_steps_render()
    {
        $steps = [
            1 => 'Onboarding/Welcome',
            2 => 'Onboarding/Language',
            3 => 'Onboarding/CookieConsent',
            4 => 'Onboarding/Region',
            5 => 'Onboarding/Communities',
            6 => 'Onboarding/Interests',
            7 => 'Onboarding/FollowLocals',
            8 => 'Onboarding/Account',
            9 => 'Onboarding/Completion',
        ];

        foreach ($steps as $step => $component) {
            $response = $this->get(route('onboarding.step', ['step' => $step]));
            $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) => $page
                    ->component($component)
                );
        }
    }

    public function test_onboarding_completion_redirects_to_discover()
    {
        $response = $this->post(route('onboarding.complete'));
        $response->assertRedirect(route('discover'));
        $this->assertTrue(session('onboarding_completed'));
    }

    public function test_campaign_landing_renders()
    {
        $campaign = Campaign::create([
            'name' => ['en' => 'Tafelen in Tenerife'],
            'slug' => 'tafelen-in-tenerife',
            'is_active' => true,
        ]);

        $response = $this->get(route('campaign.show', ['slug' => 'tafelen-in-tenerife']));
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Campaign/Landing')
                ->where('campaign.slug', 'tafelen-in-tenerife')
            );
    }

    public function test_business_claim_landing_renders()
    {
        $claimToken = ClaimToken::create([
            'spot_id' => $this->spot->id,
            'token' => 'test-token',
            'email' => 'test@example.com',
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->get(route('claim.show', ['token' => 'test-token']));
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Business/ClaimLanding')
                ->where('token', 'test-token')
            );
    }

    public function test_business_claim_form_renders()
    {
        $claimToken = ClaimToken::create([
            'spot_id' => $this->spot->id,
            'token' => 'test-token-form',
            'email' => 'test@example.com',
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->get(route('claim.form', ['token' => 'test-token-form']));
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Business/ClaimForm')
                ->where('token', 'test-token-form')
            );
    }
}
