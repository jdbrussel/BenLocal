<?php

namespace App\Services\Feed;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalizedFeedService
{
    /**
     * Get personalized feed for a user.
     *
     * Ranking considerations:
     * - Followed users (high priority)
     * - Current region (high priority)
     * - Communities (medium priority)
     * - User language (medium priority)
     * - Recency (decay factor)
     */
    public function getPersonalizedFeed(Builder $query, User $user, Request $request)
    {
        $followedUserIds = $user->follows()->pluck('followed_id')->toArray();
        $followedIdsStr = implode(',', $followedUserIds) ?: '0';

        $userCommunityId = $user->community_id;
        $userLanguage = $user->preferred_language ?? app()->getLocale();
        $userRegionId = $request->get('current_region_id') ?? $user->residence_region_id;

        $isSqlite = DB::connection()->getDriverName() === 'sqlite';

        // Apply scoring
        $query->select('timeline_events.*');

        if ($isSqlite) {
            $query->selectRaw("
                (
                    (CASE WHEN user_id IN ({$followedIdsStr}) THEN 100 ELSE 0 END) +
                    (CASE WHEN region_id = ? THEN 50 ELSE 0 END) +
                    (CASE WHEN EXISTS (SELECT 1 FROM users u WHERE u.id = timeline_events.user_id AND u.community_id = ?) THEN 30 ELSE 0 END) +
                    (CASE WHEN EXISTS (SELECT 1 FROM users u WHERE u.id = timeline_events.user_id AND u.preferred_language = ?) THEN 20 ELSE 0 END)
                ) as rank_score
            ", [$userRegionId ?: 0, $userCommunityId ?: 0, $userLanguage]);
        } else {
            $query->selectRaw("
                (
                    (CASE WHEN user_id IN ({$followedIdsStr}) THEN 100 ELSE 0 END) +
                    (CASE WHEN region_id = ? THEN 50 ELSE 0 END) +
                    (CASE WHEN EXISTS (SELECT 1 FROM users u WHERE u.id = timeline_events.user_id AND u.community_id = ?) THEN 30 ELSE 0 END) +
                    (CASE WHEN EXISTS (SELECT 1 FROM users u WHERE u.id = timeline_events.user_id AND u.preferred_language = ?) THEN 20 ELSE 0 END) +
                    (LOG10(1 + (1 / (TIMESTAMPDIFF(HOUR, created_at, NOW()) + 1))) * 10)
                ) as rank_score
            ", [$userRegionId ?: 0, $userCommunityId ?: 0, $userLanguage]);
        }

        $query->orderByDesc('rank_score')
            ->orderByDesc('created_at');

        return $query->paginate($request->get('per_page', 20));
    }
}
