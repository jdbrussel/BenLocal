<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'community_id' => $this->community_id,
            'community_name' => $this->community ? $this->community->getTranslation('name', app()->getLocale()) : null,
            'percentage' => (float) $this->percentage,
            'confidence_score' => (float) $this->confidence_score,
        ];
    }
}
