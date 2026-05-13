<?php

namespace App\Services\AI\Providers;

use App\Contracts\AI\AIProviderInterface;
use Illuminate\Support\Facades\Log;

class OpenAIProvider implements AIProviderInterface
{
    public function translate(string $text, string $targetLanguage, ?string $sourceLanguage = null): string
    {
        Log::info("OpenAI: Translating to $targetLanguage", ['text' => $text]);

        // Placeholder implementation
        return "[OpenAI Translated ($targetLanguage)] $text";
    }

    public function enrich(array $data, string $context): array
    {
        Log::info("OpenAI: Enriching spot", ['data' => $data, 'context' => $context]);

        // Placeholder implementation
        return array_merge($data, [
            'official_name' => ($data['name'] ?? 'Unknown') . ' (Enriched)',
            'phone' => '+34 900 000 000',
            'website' => 'https://example.com',
            'confidence_score' => 0.85,
            'source_references' => ['Google Maps', 'Official Website'],
        ]);
    }
}
