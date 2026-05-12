<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserSettingsService
{
    /**
     * Update user settings/preferences.
     */
    public function updateSettings(User $user, array $settings): User
    {
        $user->update(array_intersect_key($settings, array_flip([
            'preferred_language',
            'preferred_theme',
            'residence_region_id',
            'community_id',
        ])));

        return $user;
    }

    /**
     * Get user onboarding state.
     */
    public function getOnboardingState(User $user): array
    {
        return [
            'language_set' => !empty($user->preferred_language),
            'region_set' => !empty($user->residence_region_id),
            'community_set' => !empty($user->community_id),
            'email_verified' => !empty($user->email_verified_at),
            'is_complete' => !empty($user->residence_region_id) && !empty($user->community_id)
        ];
    }
}
