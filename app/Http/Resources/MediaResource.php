<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => url($this->file_path),
            'mime_type' => $this->mime_type,
            'alt_text' => $this->getTranslation('alt_text', app()->getLocale()),
            'is_primary' => $this->is_primary,
        ];
    }
}
