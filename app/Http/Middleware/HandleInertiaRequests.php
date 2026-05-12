<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'avatar' => $request->user()->avatar,
                    'preferred_language' => $request->user()->preferred_language,
                    'preferred_theme' => $request->user()->preferred_theme,
                    'onboarding_completed' => $request->user()->email_verified_at !== null, // Temporary onboarding check
                ] : null,
            ],
            'locale' => app()->getLocale(),
            'translations' => $this->getTranslations(),
            'config' => [
                'available_languages' => config('benlocal.available_languages'),
            ],
            'flash' => [
                'message' => $request->session()->get('message'),
            ],
        ]);
    }

    protected function getTranslations(): array
    {
        $locale = app()->getLocale();
        $files = ['auth', 'pagination', 'passwords', 'validation', 'messages'];
        $translations = [];

        foreach ($files as $file) {
            $path = lang_path("{$locale}/{$file}.php");
            if (file_exists($path)) {
                $translations[$file] = require $path;
            }
        }

        // Also check for JSON translations
        $jsonPath = lang_path("{$locale}.json");
        if (file_exists($jsonPath)) {
            $translations = array_merge($translations, json_decode(file_get_contents($jsonPath), true) ?: []);
        }

        return $translations;
    }
}
