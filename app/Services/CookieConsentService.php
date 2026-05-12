<?php

namespace App\Services;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class CookieConsentService
{
    protected array $categories;

    public function __construct()
    {
        $this->categories = config('benlocal.cookie_consent.categories', ['necessary']);
    }

    /**
     * Get current consent settings.
     */
    public function getConsent(): array
    {
        $default = array_fill_keys($this->categories, false);
        $default['necessary'] = true;

        $cookie = Cookie::get('benlocal_consent');
        if ($cookie) {
            $saved = json_decode($cookie, true);
            return array_merge($default, is_array($saved) ? $saved : []);
        }

        return $default;
    }

    /**
     * Update consent settings.
     */
    public function updateConsent(array $consent): void
    {
        $safeConsent = ['necessary' => true];
        foreach ($this->categories as $category) {
            if ($category === 'necessary') continue;
            $safeConsent[$category] = (bool) ($consent[$category] ?? false);
        }

        Cookie::queue('benlocal_consent', json_encode($safeConsent), 60 * 24 * 365);
    }

    /**
     * Check if a specific category is allowed.
     */
    public function isAllowed(string $category): bool
    {
        if ($category === 'necessary') return true;

        $consent = $this->getConsent();
        return (bool) ($consent[$category] ?? false);
    }
}
