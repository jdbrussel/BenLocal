<?php

namespace App\Policies;

use App\Models\ReviewPhoto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPhotoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ReviewPhoto $reviewPhoto): bool
    {
        return true;
    }

    public function create(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }

    public function update(User $user, ReviewPhoto $reviewPhoto): bool
    {
        return $user->id === $reviewPhoto->review->user_id;
    }

    public function delete(User $user, ReviewPhoto $reviewPhoto): bool
    {
        return $user->id === $reviewPhoto->review->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReviewPhoto $reviewPhoto): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReviewPhoto $reviewPhoto): bool
    {
        return false;
    }
}
