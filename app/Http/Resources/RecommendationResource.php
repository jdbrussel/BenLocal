<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
            ],
            'spot_id' => $this->spot_id,
            'motivation' => $this->motivation,
            'original_language' => $this->original_language,
            'is_translated' => $this->original_language !== app()->getLocale(),
            'hidden_gem_candidate' => $this->hidden_gem_candidate,
            'moderation_status' => $this->moderation_status,
            'created_at' => $this->created_at,
        ];
    }
}
