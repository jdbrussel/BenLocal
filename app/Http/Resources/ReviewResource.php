<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'recommendation_id' => $this->recommendation_id,
            'overall_rating' => $this->overall_rating,
            'rating_values' => $this->rating_values,
            'review_text' => $this->review_text,
            'original_language' => $this->original_language,
            'is_translated' => $this->original_language !== app()->getLocale(),
            'visited_at' => $this->visited_at,
            'confirms_recommendation' => $this->confirms_recommendation,
            'verified_visit' => $this->verified_visit,
            'weight' => (float) $this->weight,
            'visibility_score' => (float) $this->visibility_score,
            'local_status' => $this->user_region_status_at_time,
            'moderation_status' => $this->moderation_status,
            'reaction_summary' => new ReviewReactionSummaryResource($this->reactions),
            'user_reaction' => $request->user() ? $this->reactions->where('user_id', $request->user()->id)->first()?->reaction : null,
            'photos' => RecommendationResource::collection($this->whenLoaded('photos')), // Placeholder or custom photo resource
            'tagged_users' => $this->taggedUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name]),
            'created_at' => $this->created_at,
        ];
    }
}
