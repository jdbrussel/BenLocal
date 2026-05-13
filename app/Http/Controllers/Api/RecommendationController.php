<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecommendationRequest;
use App\Http\Resources\RecommendationResource;
use App\Models\Recommendation;
use App\Models\Spot;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RecommendationController extends Controller
{
    protected $service;

    public function __construct(RecommendationService $service)
    {
        $this->service = $service;
    }

    public function index(Spot $spot)
    {
        $recommendations = $this->service->getRecommendationsForSpot($spot);
        return RecommendationResource::collection($recommendations);
    }

    public function store(RecommendationRequest $request, Spot $spot)
    {
        if (!Gate::allows('create', [Recommendation::class, $spot])) {
            return response()->json([
                'message' => 'Only locals can recommend spots in this region.'
            ], 403);
        }

        $recommendation = $this->service->createRecommendation($request->user(), $spot, $request->validated());
        return new RecommendationResource($recommendation);
    }

    public function update(RecommendationRequest $request, Recommendation $recommendation)
    {
        $this->authorize('update', $recommendation);
        $recommendation = $this->service->updateRecommendation($recommendation, $request->validated());
        return new RecommendationResource($recommendation);
    }

    public function destroy(Recommendation $recommendation)
    {
        $this->authorize('delete', $recommendation);
        $this->service->deleteRecommendation($recommendation);
        return response()->noContent();
    }
}
