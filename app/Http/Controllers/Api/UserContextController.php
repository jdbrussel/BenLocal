<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserMiniResource;

class UserContextController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user ? new UserMiniResource($user) : null,
            'locale' => app()->getLocale(),
            'theme' => $user ? $user->preferred_theme : ($request->cookie('theme') ?? 'system'),
            'region' => $user ? $user->residence_region_id : null,
            'community_id' => $user ? $user->community_id : null,
            'local_statuses' => $user ? $user->regionStatuses()->with('region')->get() : [],
            'cookie_consent' => $user ? $user->cookieConsents()->latest()->first() : null,
            'onboarding_completed' => $user ? $user->onboarding_completed : false, // Check if this field exists
        ]);
    }
}
