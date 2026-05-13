<?php

namespace App\Services\AI\Providers;

use App\Contracts\AI\AIProviderInterface;
use Illuminate\Support\Facades\Log;

class DeepLProvider implements AIProviderInterface
{
    public function translate(string $text, string $targetLanguage, ?string $sourceLanguage = null): string
    {
        Log::info("DeepL: Translating to $targetLanguage", ['text' => $text]);

        // Placeholder implementation
        return "[DeepL Translated ($targetLanguage)] $text";
    }

    public function enrich(array $data, string $context): array
    {
        // DeepL doesn't typically do enrichment, but we satisfy the interface
        Log::warning("DeepL: Enrichment not supported, returning original data");
        return $data;
    }
}
