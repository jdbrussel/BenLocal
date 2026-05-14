<?php

namespace App\Services;

use App\Models\Spot;

class SubscriptionService
{
    /**
     * Define feature permissions per plan.
     */
    protected array $planFeatures = [
        'free' => [
            'photos_limit' => 3,
            'can_respond_reviews' => true,
            'can_access_analytics' => false,
            'can_add_offers' => false,
            'can_add_events' => false,
            'has_competitor_ads' => true,
        ],
        'pro' => [
            'photos_limit' => 10,
            'can_respond_reviews' => true,
            'can_access_analytics' => true,
            'can_add_offers' => true,
            'can_add_events' => true,
            'has_competitor_ads' => false,
        ],
        'premium' => [
            'photos_limit' => 999,
            'can_respond_reviews' => true,
            'can_access_analytics' => true,
            'can_add_offers' => true,
            'can_add_events' => true,
            'has_competitor_ads' => false,
        ],
    ];

    /**
     * Check if a spot has access to a specific feature.
     */
    public function canAccessFeature(Spot $spot, string $feature): bool
    {
        $plan = $spot->plan_type;
        $permissions = $this->planFeatures[$plan] ?? $this->planFeatures['free'];

        if (isset($permissions[$feature])) {
            return (bool) $permissions[$feature];
        }

        return false;
    }

    /**
     * Get a numeric limit for a feature (e.g., photo limit).
     */
    public function getFeatureLimit(Spot $spot, string $feature): int
    {
        $plan = $spot->plan_type;
        $permissions = $this->planFeatures[$plan] ?? $this->planFeatures['free'];

        return (int) ($permissions[$feature] ?? 0);
    }

    /**
     * Get all permissions for a spot.
     */
    public function getSpotPermissions(Spot $spot): array
    {
        $plan = $spot->plan_type;
        return $this->planFeatures[$plan] ?? $this->planFeatures['free'];
    }
}
