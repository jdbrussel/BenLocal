<?php

namespace App\Services\Campaign;

use App\Models\Spot;
use Illuminate\Support\Collection;

class CampaignSpotMatchingService
{
    public function findMatches(string $query, ?int $regionId = null): Collection
    {
        $spots = Spot::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('name->en', 'like', "%{$query}%")
                  ->orWhere('name->nl', 'like', "%{$query}%")
                  ->orWhere('name->es', 'like', "%{$query}%");
            });

        if ($regionId) {
            $spots->where('region_id', $regionId);
        }

        return $spots->limit(5)->get();
    }
}
