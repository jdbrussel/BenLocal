<?php

namespace App\Services\Feed;

use App\Models\TimelineEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FeedService
{
    public function getFeed(Request $request)
    {
        $user = $request->user();
        $query = TimelineEvent::query()
            ->with(['user', 'eventable', 'region']);

        $this->applyFilters($query, $request);

        if ($user) {
            // Personalized ranking logic will be integrated here or via a dedicated service
            $personalizedService = app(PersonalizedFeedService::class);
            return $personalizedService->getPersonalizedFeed($query, $user, $request);
        }

        // Guest fallback: just latest events
        return $query->latest()->paginate($request->get('per_page', 20));
    }

    public function getUserActivity(User $user, Request $request)
    {
        return TimelineEvent::where('user_id', $user->id)
            ->with(['user', 'eventable', 'region'])
            ->latest()
            ->paginate($request->get('per_page', 20));
    }

    protected function applyFilters(Builder $query, Request $request)
    {
        if ($request->has('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
    }
}
