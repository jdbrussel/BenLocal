<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaceResource extends JsonResource
{
    use HasTranslatableFields;

    public function toArray(Request $request): array
    {
        $name = $this->translated('name');

        if (!$name) {
            $name = ucfirst(str_replace('-', ' ', $this->slug));
        }

        return [
            'id' => $this->id,
            'area_id' => $this->area_id,
            'name' => $name,
            'slug' => $this->slug,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => $this->is_active,
            'spots_count' => $this->whenCounted('spots'),
        ];
    }
}
