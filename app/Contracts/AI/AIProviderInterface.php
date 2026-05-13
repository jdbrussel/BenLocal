<?php

namespace App\Contracts\AI;

interface AIProviderInterface
{
    /**
     * Translate text from source language to target language.
     */
    public function translate(string $text, string $targetLanguage, ?string $sourceLanguage = null): string;

    /**
     * Enrich data for a given entity or prompt.
     */
    public function enrich(array $data, string $context): array;
}
