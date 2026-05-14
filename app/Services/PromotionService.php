<?php

namespace App\Services;

use App\Models\Spot;
use App\Models\Offer;
use App\Models\Event;

class PromotionService
{
    /**
     * Get active offers for a spot.
     */
    public function getActiveOffers(Spot $spot)
    {
        return $spot->offers()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->get();
    }

    /**
     * Get upcoming events for a spot.
     */
    public function getUpcomingEvents(Spot $spot)
    {
        return $spot->events()
            ->where('is_active', true)
            ->where('ends_at', '>=', now())
            ->orderBy('starts_at')
            ->get();
    }

    /**
     * Create a new offer for a spot.
     */
    public function createOffer(Spot $spot, array $data): Offer
    {
        return $spot->offers()->create($data);
    }

    /**
     * Create a new event for a spot.
     */
    public function createEvent(Spot $spot, array $data): Event
    {
        return $spot->events()->create($data);
    }
}
