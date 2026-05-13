<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Spot;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $service;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }

    public function index(Spot $spot)
    {
        $reviews = $this->service->getReviewsForSpot($spot);
        return ReviewResource::collection($reviews);
    }

    public function store(ReviewRequest $request, Spot $spot)
    {
        $review = $this->service->createReview($request->user(), $spot, $request->validated());
        return new ReviewResource($review);
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);
        $review = $this->service->updateReview($review, $request->validated());
        return new ReviewResource($review);
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $this->service->deleteReview($review);
        return response()->noContent();
    }
}
