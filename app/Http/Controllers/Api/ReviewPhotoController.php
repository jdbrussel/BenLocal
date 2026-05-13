<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewPhotoResource;
use App\Models\Review;
use App\Models\ReviewPhoto;
use App\Services\ReviewPhotoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewPhotoController extends Controller
{
    protected $service;

    public function __construct(ReviewPhotoService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request, Review $review)
    {
        if (!Gate::allows('create', [ReviewPhoto::class, $review])) {
            abort(403);
        }

        $request->validate([
            'photo' => 'required|image|max:5120', // 5MB
            'caption' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $photo = $this->service->uploadPhoto($review, $request->file('photo'), $request->only(['caption', 'sort_order']));

        return new ReviewPhotoResource($photo);
    }

    public function destroy(ReviewPhoto $photo)
    {
        $this->authorize('delete', $photo);
        $this->service->deletePhoto($photo);

        return response()->noContent();
    }
}
