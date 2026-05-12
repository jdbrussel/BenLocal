<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationPreviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserMiniResource($this->whenLoaded('user')),
            'community_id' => $this->user?->community_id,
            'motivation' => $this->getTranslation('motivation', app()->getLocale()),
            'is_hidden_gem' => $this->is_hidden_gem,
            'confidence_score' => (float) $this->confidence_score,
            'created_at' => $this->created_at,
        ];
    }
}
