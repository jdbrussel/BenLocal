<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignRecommendation;
use App\Models\CampaignSubmission;
use App\Models\Category;
use App\Models\Region;
use App\Models\Sector;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $tenerife = Region::where('slug', 'tenerife')->first();
        $foodDrinks = Sector::where('slug', 'food-drinks')->first();
        $restaurants = Category::where('slug', 'restaurants')->first();
        $jan = User::where('email', 'jan@benlocal.test')->first();

        $campaign = Campaign::updateOrCreate(
            ['slug' => 'tafelen-in-tenerife'],
            [
                'name' => ['nl' => 'Tafelen in Tenerife', 'en' => 'Dining in Tenerife'],
                'description' => ['nl' => 'Campagne om de beste restaurants van Tenerife te ontdekken.'],
                'source_type' => 'facebook_group',
                'source_name' => 'Tafelen in Tenerife',
                'region_id' => $tenerife->id,
                'sector_id' => $foodDrinks->id,
                'category_id' => $restaurants->id,
                'landing_title' => ['nl' => 'Wat is jouw favoriete restaurant op Tenerife?'],
                'landing_intro' => ['nl' => 'Nominieer jouw favoriete restaurant. De leukste inzendingen maken kans op gratis publicatie in TFS Magazine in het katern Lokaal genieten met BenLocal.'],
                'cta_text' => ['nl' => 'Geef mijn favoriet door'],
                'success_message' => ['nl' => 'Bedankt voor je aanbeveling! Wil je nog een restaurant aanbevelen?'],
                'ai_enrichment_enabled' => true,
                'notify_spot_by_email' => true,
                'requires_facebook_login' => true,
                'is_active' => true,
            ]
        );

        $submissions = [
            [
                'submitted_name' => 'Bodega San Miguel',
                'submitted_notes' => 'Echt heerlijk gegeten hier, de wijn is top.',
                'matched_spot_slug' => 'bodega-san-miguel',
                'user' => $jan,
                'status' => 'processed',
                'shortlisted' => true,
                'selected_for_publication' => true,
            ],
            [
                'submitted_name' => 'Guachinche Casa Pepe',
                'submitted_notes' => 'Echt een verborgen parel!',
                'matched_spot_slug' => 'guachinche-casa-pepe',
                'user' => $jan,
                'status' => 'processed',
                'shortlisted' => true,
                'selected_for_publication' => true,
            ],
            [
                'submitted_name' => 'Nieuw Restaurantje',
                'submitted_notes' => 'Nog niet op de kaart, maar erg goed.',
                'status' => 'pending',
                'ai_result' => ['name' => 'Nieuw Restaurantje', 'category' => 'Restaurant'],
            ],
        ];

        foreach ($submissions as $data) {
            $matchedSpot = isset($data['matched_spot_slug']) ? Spot::where('slug', $data['matched_spot_slug'])->first() : null;

            $submission = CampaignSubmission::create([
                'campaign_id' => $campaign->id,
                'user_id' => $data['user']->id ?? null,
                'submitted_name' => $data['submitted_name'],
                'submitted_notes' => $data['submitted_notes'],
                'matched_spot_id' => $matchedSpot?->id,
                'status' => $data['status'],
                'ai_result' => $data['ai_result'] ?? null,
            ]);

            if (isset($data['shortlisted']) || isset($data['selected_for_publication'])) {
                CampaignRecommendation::create([
                    'campaign_id' => $campaign->id,
                    'submission_id' => $submission->id,
                    'user_id' => $submission->user_id,
                    'spot_id' => $submission->matched_spot_id,
                    'selected_for_publication' => $data['selected_for_publication'] ?? false,
                    'publication_status' => ($data['selected_for_publication'] ?? false) ? 'published' : 'draft',
                ]);
            }
        }
    }
}
