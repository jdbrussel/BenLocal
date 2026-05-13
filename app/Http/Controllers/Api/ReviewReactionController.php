<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewReactionRequest;
use App\Models\Review;
use App\Models\ReviewReaction;
use App\Services\ReviewReactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewReactionController extends Controller
{
    protected $service;

    public function __construct(ReviewReactionService $service)
    {
        $this->service = $service;
    }

    public function store(ReviewReactionRequest $request, Review $review)
    {
        if (!Gate::allows('create', [ReviewReaction::class, $review])) {
            return response()->json([
                'message' => 'You cannot react to your own review.'
            ], 403);
        }

        $reaction = $this->service->react($request->user(), $review, $request->validated());

        return response()->json([
            'message' => 'Reaction saved.',
            'reaction' => $reaction->reaction
        ]);
    }

    public function destroy(Request $request, Review $review)
    {
        $this->service->removeReaction($request->user(), $review);

        return response()->noContent();
    }
}
