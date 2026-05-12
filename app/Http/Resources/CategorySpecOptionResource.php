<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorySpecOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'label' => $this->getTranslation('label', app()->getLocale()),
            'description' => $this->getTranslation('description', app()->getLocale()),
        ];
    }
}
