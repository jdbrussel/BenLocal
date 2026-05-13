<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpotDetailResource extends JsonResource
{
    use HasTranslatableFields;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translated('name'),
            'slug' => $this->slug,
            'description' => $this->resolveTranslatable('description'),
            'contact_info' => [
                'phone' => $this->phone,
                'email' => $this->email,
                'website' => $this->website,
                'address' => $this->address,
            ],
            'opening_hours' => $this->opening_hours,
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'region' => new RegionResource($this->whenLoaded('region')),
                'area' => new AreaResource($this->whenLoaded('area')),
                'place' => new PlaceResource($this->whenLoaded('place')),
            ],
            'category' => new CategoryResource($this->whenLoaded('category')),
            'specs' => $this->spec_values,
            'community_profile' => CommunityProfileResource::collection($this->whenLoaded('communityProfiles')),
            'badges' => BadgeResource::collection($this->whenLoaded('badges')),
            'recommendations_preview' => RecommendationPreviewResource::collection($this->whenLoaded('recommendations')),
            'reviews_preview' => ReviewPreviewResource::collection($this->whenLoaded('reviews')),
            'photos' => MediaResource::collection($this->whenLoaded('media')),
            'saved_state' => $this->is_saved ?? false,
            'user_permissions' => [
                'can_review' => true,
                'can_recommend' => $request->user() ? $request->user()->regionStatuses()->where('region_id', $this->region_id)->exists() : false,
                'can_claim' => !$this->is_claimed,
            ],
        ];
    }
}
