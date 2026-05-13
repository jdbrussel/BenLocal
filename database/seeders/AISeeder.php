<?php

namespace Database\Seeders;

use App\Models\CampaignSubmission;
use App\Models\Spot;
use Illuminate\Database\Seeder;

class AISeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pending spots needing enrichment
        Spot::factory()->count(3)->create([
            'ai_enriched' => false,
            'verified_at' => null,
            'name' => ['nl' => 'Pending Spot'],
        ]);

        // 2. Partial AI results
        Spot::factory()->create([
            'name' => ['nl' => 'Spot with AI data'],
            'ai_enriched' => true,
            'ai_enrichment_data' => [
                'official_name' => 'Officially Enriched Name',
                'phone' => '+34 123 456 789',
                'website' => 'https://enriched.com',
                'confidence_score' => 0.9,
            ],
        ]);

        // 3. Low-confidence enrichment examples
        Spot::factory()->create([
            'name' => ['nl' => 'Low Confidence Spot'],
            'ai_enriched' => true,
            'ai_enrichment_data' => [
                'official_name' => 'Maybe This Name',
                'confidence_score' => 0.3,
            ],
        ]);

        // 4. Missing translations
        Spot::factory()->create([
            'name' => ['nl' => 'Alleen Nederlands'],
            'description' => ['nl' => 'Deze beschrijving is alleen in het Nederlands.'],
            'original_language' => 'nl',
        ]);

        // 5. Failed translation example (we can simulate this by setting original_language but no translations)
        Spot::factory()->create([
            'name' => ['nl' => 'Translation Fail'],
            'original_language' => 'nl',
            'source' => 'test-fail', // Marker for testing if needed
        ]);

        // 6. Campaign submissions pending enrichment
        CampaignSubmission::factory()->count(2)->create([
            'status' => 'pending',
            'submitted_name' => 'New Spot from User',
            'submitted_place_hint' => 'Near the beach',
        ]);
    }
}
