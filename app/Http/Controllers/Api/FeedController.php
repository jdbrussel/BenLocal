<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Feed\FeedService;
use App\Http\Resources\TimelineEventResource;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    protected $feedService;

    public function __construct(FeedService $feedService)
    {
        $this->feedService = $feedService;
    }

    /**
     * GET /api/feed
     * Returns the personalized feed for the authenticated user or a general feed for guests.
     */
    public function index(Request $request)
    {
        $cacheKey = 'feed:' . ($request->user() ? 'u' . $request->user()->id : 'guest') . ':p' . $request->get('page', 1);

        $feed = \Illuminate\Support\Facades\Cache::tags(['feed'])->remember($cacheKey, now()->addMinutes(5), function() use ($request) {
            return $this->feedService->getFeed($request);
        });

        return TimelineEventResource::collection($feed);
    }

    /**
     * GET /api/users/{user}/activity
     * Returns the activity feed for a specific user.
     */
    public function userActivity(User $user, Request $request)
    {
        $activity = $this->feedService->getUserActivity($user, $request);
        return TimelineEventResource::collection($activity);
    }
}
