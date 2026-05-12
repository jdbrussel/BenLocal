<?php

use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Guest Endpoints
Route::post('/consent', [AuthApiController::class, 'updateConsent']);

// Authenticated Endpoints
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::patch('/profile', [AuthApiController::class, 'updateProfile']);
    Route::patch('/preferences', [AuthApiController::class, 'updatePreferences']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::delete('/account', [AuthApiController::class, 'deleteAccount']);
});
