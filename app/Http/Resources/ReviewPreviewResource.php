<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewPreviewResource extends JsonResource
{
    use HasTranslatableFields;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserMiniResource($this->whenLoaded('user')),
            'rating' => (float) $this->overall_rating,
            'rating_values' => $this->rating_values,
            'content' => $this->resolveTranslatable('review_text'),
            'photos' => MediaResource::collection($this->whenLoaded('media')),
            'confirms_recommendation' => $this->confirms_recommendation,
            'verified_visit' => $this->verified_visit,
            'reactions_count' => $this->whenCounted('reactions'),
            'created_at' => $this->created_at,
        ];
    }
}
