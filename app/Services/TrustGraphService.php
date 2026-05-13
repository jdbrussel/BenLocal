<?php

namespace App\Services;

use App\Models\User;
use App\Models\Review;
use App\Models\Follow;

/**
 * Class TrustGraphService
 * Manages relationships and trust weights between users.
 */
class TrustGraphService
{
    /**
     * Calculate the personal trust weight between two users.
     *
     * @param User $viewer The user viewing the content.
     * @param User $target The user who created the content.
     * @return float Trust weight multiplier (default 1.0).
     */
    public function getTrustWeight(User $viewer, User $target): float
    {
        if ($viewer->id === $target->id) {
            return 2.0; // Self-trust is highest
        }

        $weight = 1.0;

        // Check if viewer follows target
        $isFollowing = Follow::where('follower_id', $viewer->id)
            ->where('followed_id', $target->id)
            ->exists();

        if ($isFollowing) {
            $weight += 0.5;

            // Check if mutual follow
            $isFollowedBack = Follow::where('follower_id', $target->id)
                ->where('followed_id', $viewer->id)
                ->exists();

            if ($isFollowedBack) {
                $weight += 0.5;
            }
        }

        // Community similarity
        if ($viewer->community_id && $viewer->community_id === $target->community_id) {
            $weight += 0.2;
        }

        return $weight;
    }

    /**
     * Get factors relevant for personal trust ranking for a specific user.
     *
     * @param User $user
     * @return array
     */
    public function getPersonalTrustFactors(User $user): array
    {
        return [
            'followed_user_ids' => $user->follows()->pluck('followed_id')->toArray(),
            'community_id' => $user->community_id,
        ];
    }
}
