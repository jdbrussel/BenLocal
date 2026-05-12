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
        $spots = $this->discoveryService->discover($request);
        return SpotListResource::collection($spots);
    }
}
