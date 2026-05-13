<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpotListResource extends JsonResource
{
    use HasTranslatableFields;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translated('name'),
            'slug' => $this->slug,
            'image' => $this->mainImage ? url($this->mainImage->file_path) : null,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'region' => new RegionResource($this->whenLoaded('region')),
            'area' => new AreaResource($this->whenLoaded('area')),
            'place' => new PlaceResource($this->whenLoaded('place')),
            'badges' => BadgeResource::collection($this->whenLoaded('badges')),
            'recommendation_count' => $this->recommendations_count ?? 0,
            'review_count' => $this->reviews_count ?? 0,
            'average_rating' => (float) ($this->reviews_avg_overall_rating ?? 0),
            'community_profile' => CommunityProfileResource::collection($this->whenLoaded('communityProfiles')),
            'spec_values' => $this->spec_values,
            'distance' => $this->distance ?? null,
            'is_saved' => $this->is_saved ?? false,
            'lifecycle_status' => $this->lifecycle_status,
        ];
    }
}
