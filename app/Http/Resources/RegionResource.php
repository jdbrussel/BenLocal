<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    use HasTranslatableFields;

    public function toArray(Request $request): array
    {
        $name = $this->translated('name');

        // Fallback to slug if name is somehow still empty
        if (!$name) {
            $name = ucfirst(str_replace('-', ' ', $this->slug));
        }

        return [
            'id' => $this->id,
            'name' => $name,
            'slug' => $this->slug,
            'description' => $this->resolveTranslatable('description'),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => $this->is_active,
            'areas_count' => $this->whenCounted('areas'),
            'spots_count' => $this->whenCounted('spots'),
        ];
    }
}
