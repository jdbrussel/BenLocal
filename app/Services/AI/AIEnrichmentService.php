<?php

namespace App\Services\AI;

use App\Contracts\AI\AIProviderInterface;
use App\Models\Spot;
use App\Models\CampaignSubmission;
use App\Services\AI\Providers\OpenAIProvider;
use Illuminate\Support\Facades\Log;

class AIEnrichmentService
{
    protected AIProviderInterface $provider;

    public function __construct(?AIProviderInterface $provider = null)
    {
        $this->provider = $provider ?? new OpenAIProvider();
    }

    /**
     * Enrich a spot with AI data.
     */
    public function enrichSpot(Spot $spot): void
    {
        Log::info("Enriching spot ID: " . $spot->id);

        $data = [
            'name' => $spot->name,
            'address' => $spot->address,
            'website' => $spot->website,
            'phone' => $spot->phone,
        ];

        $context = "Enrich this local spot with accurate business details: official name, address, phone, email, website, opening hours, cuisine (if applicable), price class, and coordinates.";

        try {
            $enrichedData = $this->provider->enrich($data, $context);

            // AI enrichment always creates pending/unverified data
            $spot->update([
                'ai_enrichment_data' => $enrichedData,
                'ai_enriched' => true,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to enrich spot ID: " . $spot->id . " - " . $e->getMessage());
        }
    }

    /**
     * Enrich a campaign submission.
     */
    public function enrichCampaignSubmission(CampaignSubmission $submission): void
    {
        Log::info("Enriching campaign submission ID: " . $submission->id);

        $data = [
            'name' => $submission->submitted_name,
            'hint' => $submission->submitted_place_hint,
        ];

        $context = "Identify this spot from a campaign submission and provide official details.";

        try {
            $enrichedData = $this->provider->enrich($data, $context);

            $submission->update([
                'ai_result' => $enrichedData,
                'status' => 'ai_enriched',
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to enrich submission ID: " . $submission->id . " - " . $e->getMessage());
        }
    }

    /**
     * Apply AI enrichment data to the spot model.
     * Admin approval required.
     */
    public function applyEnrichment(Spot $spot): void
    {
        if (!$spot->ai_enrichment_data) {
            return;
        }

        $data = $spot->ai_enrichment_data;

        $spot->update([
            'name' => $data['official_name'] ?? $spot->name,
            'address' => $data['address'] ?? $spot->address,
            'phone' => $data['phone'] ?? $spot->phone,
            'email' => $data['email'] ?? $spot->email,
            'website' => $data['website'] ?? $spot->website,
            'opening_hours' => $data['opening_hours'] ?? $spot->opening_hours,
            'latitude' => $data['latitude'] ?? $spot->latitude,
            'longitude' => $data['longitude'] ?? $spot->longitude,
            'price_level' => $data['price_class'] ?? $spot->price_level,
            'description' => $data['short_description'] ?? $spot->description,
            'source' => $data['source'] ?? $spot->source,
            'source_reference' => isset($data['source_references']) ? implode(', ', (array) $data['source_references']) : $spot->source_reference,
            'spec_values' => array_merge($spot->spec_values ?? [], [
                'cuisine' => $data['cuisine'] ?? ($spot->spec_values['cuisine'] ?? null),
                'confidence_score' => $data['confidence_score'] ?? null,
            ]),
            'ai_enriched' => false,
            'ai_enrichment_data' => null,
            'verified_at' => now(),
        ]);
    }
}
