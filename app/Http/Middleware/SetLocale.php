<?php

namespace App\Http\Middleware;

use App\Services\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $localeService = app(LocaleService::class);
            $locale = $localeService->resolveLocale();
            App::setLocale($locale);
        } catch (\Throwable $e) {
            // If session is corrupt, clear it and use default locale
            if (str_contains($e->getMessage(), '__PHP_Incomplete_Class')) {
                $request->session()->flush();
            }
            App::setLocale(config('app.fallback_locale', 'en'));
        }

        return $next($request);
    }
}
