<?php

namespace App\Http\Resources\Concerns;

trait HasTranslatableFields
{
    /**
     * Resolve a translatable field with fallback logic.
     */
    protected function resolveTranslatable(string $field): array|string|null
    {
        if (!$this->resource) return null;

        $locale = app()->getLocale();
        $default = config('benlocal.default_language', 'en');

        $value = $this->getTranslation($field, $locale, false);
        $originalLanguage = $this->getTranslation($field, $locale) ? $locale : null;

        if (!$value) {
            $value = $this->getTranslation($field, $default, false);
            $originalLanguage = $default;
        }

        // If still no value, take the first available translation
        if (!$value) {
            $translations = $this->getTranslations($field);
            if (!empty($translations)) {
                $originalLanguage = array_key_first($translations);
                $value = $translations[$originalLanguage];
            }
        }

        return [
            'value' => $value,
            'is_translated' => $originalLanguage !== $locale,
            'original_language' => $originalLanguage,
        ];
    }

    /**
     * Get simple translated string (no metadata).
     */
    protected function translated(string $field): ?string
    {
        if (!$this->resource) return null;

        $locale = app()->getLocale();
        $val = $this->getTranslation($field, $locale);

        if (!$val) {
            $val = $this->getTranslation($field, config('benlocal.default_language', 'en'));
        }

        return $val;
    }
}
