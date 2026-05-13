<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DiscoveryController;
use App\Http\Controllers\Api\SpotController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SavedSpotController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ReviewPhotoController;
use App\Http\Controllers\Api\ReviewReactionController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\SpotVisitController;
use App\Http\Controllers\Api\NotificationController;
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
Route::get('/feed', [FeedController::class, 'index']);
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

    // Recommendations
    Route::get('/spots/{spot:id}/recommendations', [RecommendationController::class, 'index']);
    Route::post('/spots/{spot:id}/recommendations', [RecommendationController::class, 'store']);
    Route::put('/recommendations/{recommendation}', [RecommendationController::class, 'update']);
    Route::delete('/recommendations/{recommendation}', [RecommendationController::class, 'destroy']);

    // Reviews
    Route::get('/spots/{spot:id}/reviews', [ReviewController::class, 'index']);
    Route::post('/spots/{spot:id}/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // Review Photos
    Route::post('/reviews/{review}/photos', [ReviewPhotoController::class, 'store']);
    Route::delete('/review-photos/{photo}', [ReviewPhotoController::class, 'destroy']);

    // Review Reactions
    Route::post('/reviews/{review}/reaction', [ReviewReactionController::class, 'store']);
    Route::delete('/reviews/{review}/reaction', [ReviewReactionController::class, 'destroy']);

    // Spot Visits
    Route::get('/me/visits', [SpotVisitController::class, 'index']);
    Route::post('/spots/{spot}/check-in', [SpotVisitController::class, 'checkIn']);
    Route::post('/spots/{spot}/qr-check-in', [SpotVisitController::class, 'qrCheckIn']);

    // User Activity
    Route::get('/users/{user}/activity', [FeedController::class, 'userActivity']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/me/notification-preferences', [NotificationController::class, 'getPreferences']);
    Route::put('/me/notification-preferences', [NotificationController::class, 'updatePreferences']);
});
