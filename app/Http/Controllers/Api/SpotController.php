<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Http\Resources\SpotDetailResource;
use App\Http\Resources\MapMarkerResource;
use App\Services\Api\DiscoveryService;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    public function show($slug, Request $request)
    {
        $cacheKey = 'spot_detail:' . $slug . ($request->user() ? ':u' . $request->user()->id : '');

        $spot = \Illuminate\Support\Facades\Cache::tags(['spots'])->remember($cacheKey, now()->addHours(1), function() use ($slug, $request) {
            $spot = Spot::where('slug', $slug)
                ->with([
                    'category.filterSpecs.options',
                    'category.ratingSpecs.options',
                    'region', 'area', 'place', 'badges',
                    'communityProfiles.community',
                    'media',
                    'recommendations' => fn($q) => $q->with('user')->limit(5),
                    'reviews' => fn($q) => $q->with(['user', 'media'])->withCount('reactions')->limit(5)
                ])
                ->firstOrFail();

            if ($user = $request->user()) {
                $spot->is_saved = $user->savedSpots()->where('spot_id', $spot->id)->exists();
            }

            return $spot;
        });

        return new SpotDetailResource($spot);
    }

    public function map(Request $request, DiscoveryService $discoveryService)
    {
        $cacheKey = 'map_markers:' . md5(serialize($request->all()));

        $spots = \Illuminate\Support\Facades\Cache::tags(['spots', 'map'])->remember($cacheKey, now()->addMinutes(10), function() use ($request, $discoveryService) {
            // For map we usually want more items and less data
            $request->merge(['per_page' => $request->get('limit', 500)]);
            return $discoveryService->discover($request);
        });

        return MapMarkerResource::collection($spots);
    }
}
