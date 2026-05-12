<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sector_id' => $this->sector_id,
            'name' => $this->getTranslation('name', app()->getLocale()),
            'slug' => $this->slug,
            'description' => $this->getTranslation('description', app()->getLocale()),
            'icon' => $this->icon,
            'is_active' => $this->is_active,
            'filter_specs' => CategorySpecResource::collection($this->whenLoaded('filterSpecs')),
            'rating_specs' => CategorySpecResource::collection($this->whenLoaded('ratingSpecs')),
        ];
    }
}
