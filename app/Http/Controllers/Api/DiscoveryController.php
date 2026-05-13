<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\DiscoveryService;
use App\Http\Resources\SpotListResource;
use App\Http\Requests\DiscoverRequest;

class DiscoveryController extends Controller
{
    public function __construct(
        protected DiscoveryService $discoveryService
    ) {}

    public function __invoke(DiscoverRequest $request)
    {
        $cacheKey = 'discover:' . md5(serialize($request->all()) . ($request->user()?->id ?? 'guest'));

        $spots = \Illuminate\Support\Facades\Cache::tags(['spots', 'discovery'])->remember($cacheKey, now()->addMinutes(15), function() use ($request) {
            return $this->discoveryService->discover($request);
        });

        return SpotListResource::collection($spots);
    }
}
