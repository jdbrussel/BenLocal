<?php

use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('onboarding.welcome');
});

Route::post('/locale/switch', [App\Http\Controllers\LocaleController::class, 'switch'])->name('locale.switch');

Route::prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/welcome', [OnboardingController::class, 'welcome'])->name('welcome');
    Route::get('/step/{step}', [OnboardingController::class, 'step'])->name('step');
    Route::post('/complete', [OnboardingController::class, 'complete'])->name('complete');
});

// Mock routes for Nav
Route::get('/discover', function() { return Inertia::render('Discover'); })->name('discover');
Route::get('/feed', function() { return Inertia::render('Feed'); })->name('feed');
Route::get('/search', function() { return Inertia::render('Search'); })->name('search');
Route::get('/saved', function() { return Inertia::render('Saved'); })->name('saved');
Route::get('/profile', function() { return Inertia::render('Profile'); })->name('profile');
Route::get('/settings', function() { return Inertia::render('Settings'); })->name('settings');
Route::get('/spot/{slug}', function($slug) {
    return Inertia::render('SpotDetail', ['slug' => $slug]);
})->name('spot.show');

require __DIR__.'/auth.php';
