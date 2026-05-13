<?php

namespace App\Services\AI;

use App\Contracts\AI\AIProviderInterface;
use App\Services\AI\Providers\OpenAIProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AITranslationService
{
    protected AIProviderInterface $provider;

    public function __construct(?AIProviderInterface $provider = null)
    {
        $this->provider = $provider ?? new OpenAIProvider();
    }

    /**
     * Translate a model's field to multiple languages.
     */
    public function translateModelField(Model $model, string $field, array $targetLanguages, bool $force = false): void
    {
        if (!method_exists($model, 'setTranslation')) {
            Log::error("Model " . get_class($model) . " does not support translations.");
            return;
        }

        $sourceLanguage = $model->original_language ?? config('app.fallback_locale', 'nl');
        $originalValue = $model->getTranslation($field, $sourceLanguage);

        if (empty($originalValue)) {
            Log::warning("Field $field is empty for model " . get_class($model) . " ID: " . $model->id);
            return;
        }

        foreach ($targetLanguages as $lang) {
            if ($lang === $sourceLanguage) {
                continue;
            }

            // Check if translation already exists
            if (!$force && !empty($model->getTranslation($field, $lang))) {
                continue;
            }

            try {
                $translatedValue = $this->provider->translate($originalValue, $lang, $sourceLanguage);
                $model->setTranslation($field, $lang, $translatedValue);

                $model->translated_at = now();
                $model->save();
            } catch (\Exception $e) {
                Log::error("Failed to translate $field to $lang for " . get_class($model) . " ID: " . $model->id . " - " . $e->getMessage());
            }
        }
    }
}
