<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimelineEventResource extends JsonResource
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
                'initials' => $this->user->initials(),
            ],
            'type' => $this->type,
            'payload' => $this->payload,
            'region' => $this->region ? [
                'id' => $this->region->id,
                'name' => $this->region->name,
                'slug' => $this->region->slug,
            ] : null,
            'created_at' => $this->created_at,
            'rank_score' => $this->when(isset($this->rank_score), $this->rank_score),

            // Contextual data based on type
            'eventable' => $this->formatEventable(),
        ];
    }

    protected function formatEventable()
    {
        if (!$this->eventable) {
            return null;
        }

        switch ($this->type) {
            case 'recommendation':
            case 'recommendation_created':
                return [
                    'id' => $this->eventable->id,
                    'spot' => $this->eventable->spot ? [
                        'id' => $this->eventable->spot->id,
                        'name' => $this->eventable->spot->name,
                    ] : null,
                ];
            case 'review':
            case 'review_created':
                return [
                    'id' => $this->eventable->id,
                    'rating' => $this->eventable->overall_rating ?? $this->eventable->rating,
                    'text' => $this->eventable->review_text ?? $this->eventable->text,
                    'spot' => $this->eventable->spot ? [
                        'id' => $this->eventable->spot->id,
                        'name' => $this->eventable->spot->name,
                    ] : null,
                ];
            case 'review_reaction_created':
                return [
                    'id' => $this->eventable->id,
                    'reaction' => $this->eventable->reaction,
                    'review' => $this->eventable->review ? [
                        'id' => $this->eventable->review->id,
                        'spot' => $this->eventable->review->spot ? [
                            'id' => $this->eventable->review->spot->id,
                            'name' => $this->eventable->review->spot->name,
                        ] : null,
                    ] : null,
                ];
            case 'follow':
            case 'user_followed':
            case 'follow_created':
                // Check if it's a Follow model or similar
                $followed = $this->eventable->followed ?? $this->eventable->followedUser ?? null;
                return [
                    'id' => $this->eventable->id,
                    'followed_user' => $followed ? [
                        'id' => $followed->id,
                        'name' => $followed->name,
                    ] : null,
                ];
            case 'spot_saved':
            case 'saved_spot':
                return [
                    'id' => $this->eventable->id,
                    'spot' => $this->eventable->spot ? [
                        'id' => $this->eventable->spot->id,
                        'name' => $this->eventable->spot->name,
                    ] : null,
                ];
            default:
                return [
                    'id' => $this->eventable->id,
                ];
        }
    }
}
