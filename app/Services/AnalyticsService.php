<?php

namespace App\Services;

use App\Models\Spot;
use App\Models\SpotAnalytics;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Track a specific metric for a spot.
     */
    public function track(Spot $spot, string $type, ?int $userId = null, ?string $guestToken = null, ?string $source = null, array $metadata = []): void
    {
        SpotAnalytics::create([
            'spot_id' => $spot->id,
            'metric_type' => $type,
            'user_id' => $userId,
            'guest_token' => $guestToken,
            'source' => $source,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }

    /**
     * Get analytics summary for a spot within a date range.
     */
    public function getSummary(Spot $spot, $startDate, $endDate): array
    {
        return SpotAnalytics::where('spot_id', $spot->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('metric_type', DB::raw('count(*) as total'))
            ->groupBy('metric_type')
            ->pluck('total', 'metric_type')
            ->toArray();
    }

    /**
     * Get daily views for a spot.
     */
    public function getDailyViews(Spot $spot, $days = 30): array
    {
        return SpotAnalytics::where('spot_id', $spot->id)
            ->where('metric_type', 'view')
            ->where('created_at', '>=', now()->subDays($days))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();
    }
}
