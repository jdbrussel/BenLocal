<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class LocaleService
{
    /**
     * Resolve the locale based on priority:
     * 1. Authenticated user preferred_language
     * 2. Guest selected language in cookie
     * 3. Browser locale
     * 4. Default language
     */
    public function resolveLocale(): string
    {
        $available = array_keys(Config::get('benlocal.available_languages', ['en' => 'English']));
        $default = Config::get('benlocal.default_language', 'en');

        // 1. Authenticated user
        if (auth()->check() && auth()->user()->preferred_language) {
            $userLocale = auth()->user()->preferred_language;
            if (in_array($userLocale, $available)) {
                return $userLocale;
            }
        }

        // 2. Session or Cookie (for guests)
        $sessionLocale = session('locale');
        if ($sessionLocale && in_array($sessionLocale, $available)) {
            return $sessionLocale;
        }

        $cookieLocale = Request::cookie('benlocal_locale');
        if ($cookieLocale && in_array($cookieLocale, $available)) {
            return $cookieLocale;
        }

        // 3. Browser locale
        $browserLocale = $this->detectBrowserLocale();
        if ($browserLocale && in_array($browserLocale, $available)) {
            return $browserLocale;
        }

        return $default;
    }

    /**
     * Detect browser locale from Accept-Language header.
     */
    public function detectBrowserLocale(): ?string
    {
        $header = Request::header('Accept-Language');
        if (!$header) {
            return null;
        }

        $locales = explode(',', $header);
        foreach ($locales as $locale) {
            $lang = strtolower(substr(trim($locale), 0, 2));
            return $lang; // Return the first one found
        }

        return null;
    }

    /**
     * Get available languages.
     */
    public function getAvailableLanguages(): array
    {
        return Config::get('benlocal.available_languages', ['en' => 'English']);
    }
}
