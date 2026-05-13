<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignRecommendation;
use App\Models\CampaignSubmission;
use App\Models\Region;
use App\Models\Spot;
use App\Models\User;
use App\Services\Campaign\CampaignSelectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase9CampaignTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CommunitySeeder::class);
        $this->seed(\Database\Seeders\LocationSeeder::class);
    }

    public function test_campaign_page_loads()
    {
        $campaign = Campaign::factory()->create(['slug' => 'test-campaign']);

        $response = $this->get(route('campaign.show', 'test-campaign'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Campaign/Landing')
            ->has('campaign')
        );
    }

    public function test_campaign_spot_matching_works()
    {
        $region = Region::first();
        $campaign = Campaign::factory()->create([
            'slug' => 'test-campaign',
            'region_id' => $region->id
        ]);

        Spot::factory()->create([
            'name' => ['en' => 'Matching Spot'],
            'region_id' => $region->id
        ]);

        $response = $this->postJson(route('campaign.search', 'test-campaign'), [
            'query' => 'Matching',
            'region_id' => $region->id
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => ['en' => 'Matching Spot']]);
    }

    public function test_campaign_submission_creates_correctly()
    {
        $campaign = Campaign::factory()->create(['slug' => 'test-campaign']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('campaign.submit', 'test-campaign'), [
            'submitted_name' => 'New Restaurant',
            'submitted_notes' => 'Best pizza ever',
            'consent_to_terms' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('campaign_submissions', [
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'submitted_name' => 'New Restaurant',
        ]);
    }

    public function test_shortlist_and_export_works()
    {
        $campaign = Campaign::factory()->create();
        $submission = CampaignSubmission::factory()->create(['campaign_id' => $campaign->id]);
        $spot = Spot::factory()->create();
        $user = User::factory()->create();

        $rec = CampaignRecommendation::create([
            'campaign_id' => $campaign->id,
            'submission_id' => $submission->id,
            'user_id' => $user->id,
            'spot_id' => $spot->id,
            'publication_status' => 'pending',
        ]);

        $service = new CampaignSelectionService();
        $service->shortlist($rec);

        $this->assertEquals('shortlisted', $rec->fresh()->publication_status);

        $export = $service->exportCampaignData($campaign->id, 'json');
        $this->assertCount(1, $export);
        $this->assertEquals($spot->name, $export[0]['spot_name']);
    }
}
