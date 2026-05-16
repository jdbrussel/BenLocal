<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Http\Resources\SpotDetailResource;
use App\Http\Resources\MapMarkerResource;
use App\Services\Api\DiscoveryService;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function show($slug, Request $request)
    {
        $spot = Spot::where('slug', $slug)
            ->with([
                'category.filterSpecs.options',
                'category.ratingSpecs.options',
                'region', 'area', 'place', 'badges',
                'communityProfiles.community',
                'media',
                'offers' => fn($q) => $q->where('is_active', true)->where(fn($sq) => $sq->whereNull('ends_at')->orWhere('ends_at', '>=', now())),
                'events' => fn($q) => $q->where('is_active', true)->where('ends_at', '>=', now()),
                'recommendations' => fn($q) => $q->with('user')->limit(5),
            ])
            ->firstOrFail();

        // Load reviews safely
        try {
            $spot->setRelation('reviews', $spot->reviews()->with(['user', 'media'])->withCount('reactions')->limit(5)->get());
        } catch (\Throwable $e) {
            $spot->setRelation('reviews', collect());
        }

        if ($user = $request->user()) {
            $spot->is_saved = $user->savedSpots()->where('spot_id', $spot->id)->exists();
        }

        // Track view
        try {
            $this->analyticsService->track(
                $spot,
                'view',
                $request->user()?->id,
                $request->header('X-Guest-Token'),
                $request->input('source')
            );
        } catch (\Throwable $e) {
            // Silently fail for analytics to not block the request
        }

        return new SpotDetailResource($spot);
    }

    public function map(Request $request, DiscoveryService $discoveryService)
    {
        // For map we usually want more items and less data
        $request->merge(['per_page' => $request->get('limit', 500)]);
        $spots = $discoveryService->discover($request);

        return MapMarkerResource::collection($spots);
    }
}
