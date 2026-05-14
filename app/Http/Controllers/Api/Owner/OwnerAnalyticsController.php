<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Services\AnalyticsService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OwnerAnalyticsController extends Controller
{
    protected $analyticsService;
    protected $subscriptionService;

    public function __construct(AnalyticsService $analyticsService, SubscriptionService $subscriptionService)
    {
        $this->analyticsService = $analyticsService;
        $this->subscriptionService = $subscriptionService;
    }

    public function index(Request $request, Spot $spot)
    {
        // Check if user is owner of the spot (This would normally be handled by a policy/middleware)
        // $this->authorize('viewAnalytics', $spot);

        if (!$this->subscriptionService->canAccessFeature($spot, 'can_access_analytics')) {
            return response()->json([
                'error' => 'Upgrade to Pro to access analytics.',
                'can_access' => false
            ], 403);
        }

        $days = $request->input('days', 30);
        $startDate = Carbon::now()->subDays($days);
        $endDate = Carbon::now();

        return response()->json([
            'summary' => $this->analyticsService->getSummary($spot, $startDate, $endDate),
            'daily_views' => $this->analyticsService->getDailyViews($spot, $days),
            'can_access' => true
        ]);
    }
}
