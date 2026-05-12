<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorySpecResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->getTranslation('name', app()->getLocale()),
            'description' => $this->getTranslation('description', app()->getLocale()),
            'type' => $this->type,
            'unit' => $this->unit ? $this->getTranslation('unit', app()->getLocale()) : null,
            'is_required' => $this->is_required,
            'is_filterable' => $this->is_filterable ?? false,
            'options' => CategorySpecOptionResource::collection($this->whenLoaded('options')),
            'min_value' => $this->min_value ?? null,
            'max_value' => $this->max_value ?? null,
            'weight' => $this->weight ?? null,
        ];
    }
}
