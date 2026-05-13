<?php

namespace App\Policies;

use App\Models\Recommendation;
use App\Models\User;
use App\Models\Spot;
use Illuminate\Auth\Access\Response;

class RecommendationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Recommendation $recommendation): bool
    {
        return true;
    }

    public function create(User $user, Spot $spot): bool
    {
        return $user->regionStatuses()
            ->where('region_id', $spot->region_id)
            ->whereIn('status', ['local', 'verified_local'])
            ->exists();
    }

    public function update(User $user, Recommendation $recommendation): bool
    {
        return $user->id === $recommendation->user_id;
    }

    public function delete(User $user, Recommendation $recommendation): bool
    {
        return $user->id === $recommendation->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Recommendation $recommendation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recommendation $recommendation): bool
    {
        return false;
    }
}
