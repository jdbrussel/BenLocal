<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MapMarkerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->getTranslation('name', app()->getLocale()),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'category_id' => $this->category_id,
            'category_icon' => $this->category ? $this->category->icon : null,
            'recommendation_count' => $this->recommendations_count ?? 0,
            'average_rating' => (float) ($this->reviews_avg_overall_rating ?? 0),
            'preview_image' => $this->mainImage ? url($this->mainImage->file_path) : null,
            'badge' => $this->whenLoaded('badges', fn() => $this->badges->first()?->key),
        ];
    }
}
