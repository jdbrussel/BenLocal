<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'region_id' => $this->region_id,
            'name' => $this->getTranslation('name', app()->getLocale()),
            'slug' => $this->slug,
            'description' => $this->getTranslation('description', app()->getLocale()),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => $this->is_active,
            'places_count' => $this->whenCounted('places'),
            'spots_count' => $this->whenCounted('spots'),
        ];
    }
}
