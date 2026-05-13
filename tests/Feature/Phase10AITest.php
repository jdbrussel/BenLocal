<?php

namespace Tests\Feature;

use App\Models\Spot;
use App\Services\AI\AIEnrichmentService;
use App\Services\AI\AITranslationService;
use App\Services\AI\Providers\OpenAIProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase10AITest extends TestCase
{
    use RefreshDatabase;

    public function test_translation_does_not_overwrite_by_default()
    {
        $spot = Spot::factory()->create([
            'name' => [
                'nl' => 'Originele Naam',
                'en' => 'Existing English Name',
            ],
            'original_language' => 'nl',
        ]);

        $service = new AITranslationService(new OpenAIProvider());
        $service->translateModelField($spot, 'name', ['en'], false);

        $spot->refresh();
        $this->assertEquals('Existing English Name', $spot->getTranslation('name', 'en'));
    }

    public function test_translation_overwrites_when_forced()
    {
        $spot = Spot::factory()->create([
            'name' => [
                'nl' => 'Originele Naam',
                'en' => 'Existing English Name',
            ],
            'original_language' => 'nl',
        ]);

        $service = new AITranslationService(new OpenAIProvider());
        $service->translateModelField($spot, 'name', ['en'], true);

        $spot->refresh();
        $this->assertStringContainsString('[OpenAI Translated (en)]', $spot->getTranslation('name', 'en'));
    }

    public function test_ai_enrichment_creates_pending_result()
    {
        $spot = Spot::factory()->create([
            'ai_enriched' => false,
            'ai_enrichment_data' => null,
        ]);

        $service = new AIEnrichmentService(new OpenAIProvider());
        $service->enrichSpot($spot);

        $spot->refresh();
        $this->assertTrue($spot->ai_enriched);
        $this->assertNotNull($spot->ai_enrichment_data);
        $this->assertArrayHasKey('official_name', $spot->ai_enrichment_data);
    }

    public function test_admin_approval_applies_enrichment()
    {
        $spot = Spot::factory()->create([
            'name' => ['nl' => 'Old Name'],
            'ai_enriched' => true,
            'ai_enrichment_data' => [
                'official_name' => 'New Approved Name',
                'phone' => '+34 999 888 777',
            ],
        ]);

        $service = new AIEnrichmentService(new OpenAIProvider());
        $service->applyEnrichment($spot);

        $spot->refresh();
        $this->assertEquals('New Approved Name', $spot->name);
        $this->assertEquals('+34 999 888 777', $spot->phone);
        $this->assertFalse($spot->ai_enriched);
        $this->assertNull($spot->ai_enrichment_data);
        $this->assertNotNull($spot->verified_at);
    }
    public function test_translation_sets_translated_at()
    {
        $spot = Spot::factory()->create([
            'name' => ['nl' => 'Vertaal Mij'],
            'original_language' => 'nl',
            'translated_at' => null,
        ]);

        $service = new AITranslationService(new OpenAIProvider());
        $service->translateModelField($spot, 'name', ['en'], true);

        $spot->refresh();
        $this->assertNotNull($spot->translated_at);
    }

    public function test_ai_enrichment_applies_all_fields()
    {
        $spot = Spot::factory()->create([
            'name' => ['nl' => 'Old Name'],
            'ai_enriched' => true,
            'ai_enrichment_data' => [
                'official_name' => 'Full Enriched Name',
                'address' => ['street' => 'Calle Mayor', 'city' => 'Madrid'],
                'phone' => '+34 912 345 678',
                'email' => 'info@enriched.com',
                'website' => 'https://enriched.com',
                'opening_hours' => ['monday' => '09:00-18:00'],
                'latitude' => 40.416775,
                'longitude' => -3.703790,
                'price_class' => 3,
                'short_description' => 'A nice place',
                'cuisine' => 'Spanish',
                'confidence_score' => 0.95,
                'source' => 'AI Provider',
                'source_references' => ['Source A', 'Source B'],
            ],
        ]);

        $service = new AIEnrichmentService(new OpenAIProvider());
        $service->applyEnrichment($spot);

        $spot->refresh();
        $this->assertEquals('Full Enriched Name', $spot->name);
        $this->assertEquals(['street' => 'Calle Mayor', 'city' => 'Madrid'], $spot->address);
        $this->assertEquals('+34 912 345 678', $spot->phone);
        $this->assertEquals('info@enriched.com', $spot->email);
        $this->assertEquals('https://enriched.com', $spot->website);
        $this->assertEquals(['monday' => '09:00-18:00'], $spot->opening_hours);
        $this->assertEquals(40.416775, (float)$spot->latitude);
        $this->assertEquals(-3.703790, (float)$spot->longitude);
        $this->assertEquals(3, $spot->price_level);
        $this->assertEquals('A nice place', $spot->description);
        $this->assertEquals('AI Provider', $spot->source);
        $this->assertStringContainsString('Source A, Source B', $spot->source_reference);
        $this->assertEquals('Spanish', $spot->spec_values['cuisine']);
        $this->assertEquals(0.95, $spot->spec_values['confidence_score']);
    }
}
