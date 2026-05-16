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
        $feed = $this->feedService->getFeed($request);

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
