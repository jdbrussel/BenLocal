<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = \App\Models\Campaign::all();
        $submissions = \App\Models\CampaignSubmission::with(['campaign', 'user', 'matchedSpot'])->get();
        $tenerife = \App\Models\Region::where('slug', 'tenerife')->first();

        foreach ($submissions as $submission) {
            \App\Models\TimelineEvent::create([
                'user_id' => $submission->user_id,
                'type' => 'campaign_submission_created',
                'eventable_type' => \App\Models\CampaignSubmission::class,
                'eventable_id' => $submission->id,
                'region_id' => $submission->campaign->region_id ?? $tenerife->id,
                'payload' => [
                    'campaign_id' => $submission->campaign_id,
                    'campaign_name' => $submission->campaign->name,
                    'submitted_name' => $submission->submitted_name,
                    'spot_id' => $submission->matched_spot_id,
                    'publication_status' => $submission->status,
                ],
                'created_at' => $submission->created_at,
            ]);
        }

        // Add some "business claim" simulation events
        $spots = \App\Models\Spot::all();
        for ($i = 0; $i < 15; $i++) {
            $spot = $spots->random();
            \App\Models\TimelineEvent::create([
                'user_id' => null,
                'type' => 'business_claim_created',
                'eventable_type' => \App\Models\Spot::class,
                'eventable_id' => $spot->id,
                'region_id' => $spot->region_id ?? $tenerife->id,
                'payload' => [
                    'spot_id' => $spot->id,
                    'spot_name' => $spot->name,
                ],
                'created_at' => now()->subDays(rand(1, 10)),
            ]);
        }
    }
}
