<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class LocaleController extends Controller
{
    /**
     * Switch the application locale.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|in:' . implode(',', array_keys(config('benlocal.available_languages'))),
        ]);

        $locale = $request->locale;

        if (auth()->check()) {
            $user = auth()->user();
            $user->preferred_language = $locale;
            $user->save();
        }

        // Always set a cookie for guests or as a fallback
        Cookie::queue('benlocal_locale', $locale, 60 * 24 * 365); // 1 year

        return back();
    }
}
