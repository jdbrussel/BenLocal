<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DiscoveryController;
use App\Http\Controllers\Api\SpotController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SavedSpotController;
use App\Http\Controllers\Api\UserContextController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Endpoints
Route::get('/me/context', UserContextController::class);

Route::get('/regions', [RegionController::class, 'index']);
Route::get('/regions/{region:slug}', [RegionController::class, 'show']);
Route::get('/regions/{region:slug}/areas', [RegionController::class, 'areas']);
Route::get('/areas/{area:slug}/places', [AreaController::class, 'places']);

Route::get('/sectors', [CategoryController::class, 'sectors']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);
Route::get('/categories/{category:slug}/specs', [CategoryController::class, 'specs']);

Route::get('/discover', DiscoveryController::class);
Route::get('/map/spots', [SpotController::class, 'map']);
Route::get('/spots/{slug}', [SpotController::class, 'show']);
Route::get('/search', SearchController::class);

Route::post('/consent', [AuthApiController::class, 'updateConsent']);

// Authenticated Endpoints
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::patch('/profile', [AuthApiController::class, 'updateProfile']);
    Route::patch('/preferences', [AuthApiController::class, 'updatePreferences']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::delete('/account', [AuthApiController::class, 'deleteAccount']);

    // Saved Spots
    Route::get('/saved-spots', [SavedSpotController::class, 'index']);
    Route::post('/spots/{spot:slug}/save', [SavedSpotController::class, 'store']);
    Route::delete('/spots/{spot:slug}/save', [SavedSpotController::class, 'destroy']);
});
