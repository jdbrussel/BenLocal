<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Services\PromotionService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class OwnerPromotionController extends Controller
{
    protected $promotionService;
    protected $subscriptionService;

    public function __construct(PromotionService $promotionService, SubscriptionService $subscriptionService)
    {
        $this->promotionService = $promotionService;
        $this->subscriptionService = $subscriptionService;
    }

    public function getOffers(Spot $spot)
    {
        return response()->json($spot->offers);
    }

    public function createOffer(Request $request, Spot $spot)
    {
        if (!$this->subscriptionService->canAccessFeature($spot, 'can_add_offers')) {
            return response()->json(['error' => 'Upgrade to Pro to add offers.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|array',
            'description' => 'nullable|array',
            'coupon_code' => 'nullable|string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $offer = $this->promotionService->createOffer($spot, $validated);

        return response()->json($offer, 201);
    }

    public function getEvents(Spot $spot)
    {
        return response()->json($spot->events);
    }

    public function createEvent(Request $request, Spot $spot)
    {
        if (!$this->subscriptionService->canAccessFeature($spot, 'can_add_events')) {
            return response()->json(['error' => 'Upgrade to Pro to add events.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|array',
            'description' => 'nullable|array',
            'event_type' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        $event = $this->promotionService->createEvent($spot, $validated);

        return response()->json($event, 201);
    }
}
