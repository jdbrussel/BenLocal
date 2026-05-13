<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewPhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'review_id' => $this->review_id,
            'url' => asset('storage/' . $this->path),
            'caption' => $this->caption,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
        ];
    }
}
