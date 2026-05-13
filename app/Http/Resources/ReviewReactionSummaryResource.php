<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewReactionSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reactions = $this->resource; // Assuming $this->resource is a collection or similar

        return [
            'agree' => $reactions->where('reaction', 'agree')->count(),
            'partly' => $reactions->where('reaction', 'partly')->count(),
            'disagree' => $reactions->where('reaction', 'disagree')->count(),
            'total' => $reactions->count(),
        ];
    }
}
