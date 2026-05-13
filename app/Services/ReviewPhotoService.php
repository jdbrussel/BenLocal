<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ReviewPhotoService
{
    public function uploadPhoto(Review $review, UploadedFile $file, array $data = [])
    {
        $path = $file->store('reviews/' . $review->id, 'public');

        return ReviewPhoto::create([
            'review_id' => $review->id,
            'path' => $path,
            'caption' => $data['caption'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    public function deletePhoto(ReviewPhoto $photo)
    {
        Storage::disk('public')->delete($photo->path);
        return $photo->delete();
    }
}
