<?php

namespace App\Services\Api;

use App\Models\Spot;
use App\Enums\SpotLifecycleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscoveryService
{
    public function discover(Request $request)
    {
        $query = Spot::query()->where('lifecycle_status', SpotLifecycleStatus::ACTIVE);

        $this->applyLocationFilters($query, $request);
        $this->applyCategoryFilters($query, $request);
        $this->applyCommunityFilters($query, $request);
        $this->applySpecFilters($query, $request);
        $this->applySearch($query, $request);

        $query->with(['category', 'region', 'area', 'place', 'badges', 'mainImage', 'communityProfiles.community']);
        $query->withCount(['recommendations', 'reviews']);
        $query->withAvg('reviews', 'overall_rating');

        if ($user = $request->user()) {
            $query->addSelect([
                'is_saved' => \App\Models\SavedSpot::whereColumn('spot_id', 'spots.id')
                    ->where('user_id', $user->id)
                    ->selectRaw('1')
                    ->limit(1)
            ]);

            $this->applyPersonalization($query, $user);
        }

        $this->applySorting($query, $request);

        return $query->paginate($request->get('per_page', 20));
    }

    protected function applyLocationFilters(Builder $query, Request $request)
    {
        if ($request->has('region')) {
            $query->whereHas('region', fn($q) => $q->where('slug', $request->region));
        }
        if ($request->has('area')) {
            $query->whereHas('area', fn($q) => $q->where('slug', $request->area));
        }
        if ($request->has('place')) {
            $query->whereHas('place', fn($q) => $q->where('slug', $request->place));
        }

        if ($request->has(['latitude', 'longitude'])) {
            $lat = (float) $request->latitude;
            $lon = (float) $request->longitude;
            $radius = (float) $request->get('radius', 10); // km

            $query->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lon, $lat])
                ->having('distance', '<=', $radius);
        }
    }

    protected function applyCategoryFilters(Builder $query, Request $request)
    {
        if ($request->has('sector')) {
            $query->whereHas('sector', fn($q) => $q->where('slug', $request->sector));
        }
        if ($request->has('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
    }

    protected function applyCommunityFilters(Builder $query, Request $request)
    {
        if ($request->has('communities')) {
            $communities = (array) $request->communities;
            $query->whereHas('communityProfiles', function ($q) use ($communities) {
                $q->whereIn('community_id', $communities);
            });
        }
    }

    protected function applySpecFilters(Builder $query, Request $request)
    {
        if ($request->has('filters')) {
            $filters = $request->filters; // Expecting [key => value]
            if (is_array($filters)) {
                foreach ($filters as $key => $value) {
                    $query->where('spec_values->' . $key, $value);
                }
            }
        }

        if ($request->has('price_class')) {
            $query->where('price_level', $request->price_class);
        }
    }

    protected function applySearch(Builder $query, Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
    }

    protected function applyPersonalization(Builder $query, \App\Models\User $user)
    {
        $followedUserIds = $user->follows()->pluck('followed_id')->toArray();
        $userCommunityId = $user->community_id;

        $followedIdsStr = implode(',', $followedUserIds) ?: '0';

        $query->selectRaw("(
            recommendation_score * 1.0 +
            review_score * 0.8 +
            local_trust_score * 1.2 +
            (CASE WHEN EXISTS (SELECT 1 FROM recommendations WHERE recommendations.spot_id = spots.id AND recommendations.user_id IN ({$followedIdsStr})) THEN 50 ELSE 0 END) +
            (CASE WHEN EXISTS (SELECT 1 FROM spot_community_profiles WHERE spot_community_profiles.spot_id = spots.id AND spot_community_profiles.community_id = ?) THEN 30 ELSE 0 END)
        ) as personalized_score", [$userCommunityId ?: 0]);
    }

    protected function applySorting(Builder $query, Request $request)
    {
        $sort = $request->get('sort', 'recommended_for_you');

        switch ($sort) {
            case 'newest':
                $query->latest();
                break;
            case 'most_recommended':
                $query->orderBy('recommendations_count', 'desc');
                break;
            case 'highest_rated':
                $query->orderBy('reviews_avg_overall_rating', 'desc');
                break;
            case 'nearest':
                if ($request->has(['latitude', 'longitude'])) {
                    $query->orderBy('distance', 'asc');
                } else {
                    $query->latest();
                }
                break;
            case 'hidden_gems':
                $query->orderBy('hidden_gem_score', 'desc');
                break;
            case 'trusted_locals':
                $query->orderBy('local_trust_score', 'desc');
                break;
            case 'tourist_favourites':
                $query->orderBy('tourist_saturation_score', 'desc');
                break;
            case 'authentic_local':
                $query->where('tourist_saturation_score', '<', 30)
                    ->orderBy('local_trust_score', 'desc');
                break;
            case 'community_match':
                $query->orderBy('community_match_score', 'desc');
                break;
            case 'recommended_for_you':
            default:
                if ($request->user()) {
                    $query->orderBy('personalized_score', 'desc');
                } else {
                    $query->orderBy('recommendation_score', 'desc');
                }
                break;
        }
    }
}
