<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignRecommendation;
use App\Models\CampaignSubmission;
use App\Models\Community;
use App\Models\Region;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Phase9CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $region = Region::where('slug', 'tenerife')->first();
        $community = Community::where('slug', 'netherlands')->first();

        $campaign = Campaign::updateOrCreate(
            ['slug' => 'tafelen-in-tenerife'],
            [
                'name' => [
                    'en' => 'Tafelen in Tenerife',
                    'nl' => 'Tafelen in Tenerife',
                    'es' => 'Cenar en Tenerife',
                ],
                'description' => [
                    'en' => 'Discover the best restaurants in Tenerife as recommended by the community.',
                    'nl' => 'Ontdek de beste restaurants op Tenerife volgens onze community.',
                    'es' => 'Descubre los mejores restaurantes de Tenerife recomendados por la comunidad.',
                ],
                'source_type' => 'facebook_group',
                'source_name' => 'Tafelen in Tenerife',
                'source_url' => 'https://www.facebook.com/groups/tafelenintenerife',
                'region_id' => $region?->id,
                'default_community_id' => $community?->id,
                'landing_title' => [
                    'en' => 'Submit Your Favorite Restaurant',
                    'nl' => 'Stuur je favoriete restaurant in',
                ],
                'landing_intro' => [
                    'en' => 'Help others find the best spots! Selected restaurants will be featured in TFS Magazine.',
                    'nl' => 'Help anderen de beste plekjes te vinden! Geselecteerde restaurants komen in TFS Magazine.',
                ],
                'cta_text' => [
                    'en' => 'Submit Recommendation',
                    'nl' => 'Aanbeveling versturen',
                ],
                'success_message' => [
                    'en' => 'Thank you! Your recommendation has been received.',
                    'nl' => 'Bedankt! Je aanbeveling is ontvangen.',
                ],
                'publication_context' => [
                    'en' => 'TFS Magazine Summer Edition',
                    'nl' => 'TFS Magazine Zomereditie',
                ],
                'starts_at' => now()->subMonth(),
                'ends_at' => now()->addMonths(2),
                'is_active' => true,
            ]
        );

        $users = User::all();
        $spots = Spot::where('region_id', $region?->id)->limit(20)->get();

        if ($users->isEmpty() || $spots->isEmpty()) {
            return;
        }

        // 1. Matched submissions
        foreach ($users->take(10) as $user) {
            $spot = $spots->random();
            CampaignSubmission::create([
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
                'submitted_name' => $spot->getTranslation('name', 'en'),
                'submitted_notes' => 'Great food and amazing atmosphere!',
                'matched_spot_id' => $spot->id,
                'user_confirmed_spot' => true,
                'status' => 'converted',
                'consent_to_terms' => true,
            ]);
        }

        // 2. Unknown spots (pending)
        for ($i = 0; $i < 20; $i++) {
            CampaignSubmission::create([
                'campaign_id' => $campaign->id,
                'user_id' => $users->random()->id,
                'submitted_name' => 'Unknown Restaurant ' . ($i + 1),
                'submitted_notes' => 'A hidden gem I found last week.',
                'submitted_place_hint' => 'Near Los Cristianos harbor',
                'status' => 'pending',
                'consent_to_terms' => true,
            ]);
        }

        // 3. Guest submissions
        for ($i = 0; $i < 10; $i++) {
            CampaignSubmission::create([
                'campaign_id' => $campaign->id,
                'guest_token' => Str::random(40),
                'submitted_name' => 'Guest Recommendation ' . ($i + 1),
                'submitted_notes' => 'I loved this place during my vacation.',
                'status' => 'pending',
                'consent_to_terms' => true,
            ]);
        }

        // 4. Converted recommendations
        $submissions = CampaignSubmission::where('status', 'converted')->get();
        foreach ($submissions as $submission) {
            CampaignRecommendation::create([
                'campaign_id' => $campaign->id,
                'submission_id' => $submission->id,
                'user_id' => $submission->user_id,
                'spot_id' => $submission->matched_spot_id,
                'publication_status' => 'pending',
            ]);
        }

        // 5. Shortlisted & Magazine
        $recs = CampaignRecommendation::all();
        if ($recs->count() >= 5) {
            $recs->take(3)->each->update(['publication_status' => 'shortlisted']);
            $recs->last()->update([
                'publication_status' => 'selected_for_magazine',
                'selected_for_publication' => true
            ]);
        }
    }
}
