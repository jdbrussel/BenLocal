<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class ThemePreferenceService
{
    /**
     * Resolve the theme based on priority:
     * 1. Authenticated user preferred_theme
     * 2. Guest selected theme in cookie
     * 3. Default: system
     */
    public function resolveTheme(): string
    {
        if (Auth::check() && Auth::user()->preferred_theme) {
            return Auth::user()->preferred_theme;
        }

        return Cookie::get('benlocal_theme', 'system');
    }

    /**
     * Update theme preference.
     */
    public function updateTheme(string $theme): void
    {
        $allowed = ['light', 'dark', 'system'];
        if (!in_array($theme, $allowed)) {
            $theme = 'system';
        }

        if (Auth::check()) {
            Auth::user()->update(['preferred_theme' => $theme]);
        } else {
            Cookie::queue('benlocal_theme', $theme, 60 * 24 * 365);
        }
    }
}
