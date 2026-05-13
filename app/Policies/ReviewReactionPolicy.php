<?php

namespace App\Policies;

use App\Models\ReviewReaction;
use App\Models\User;
use App\Models\Review;
use Illuminate\Auth\Access\Response;

class ReviewReactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ReviewReaction $reviewReaction): bool
    {
        return true;
    }

    public function create(User $user, Review $review): bool
    {
        return $user->id !== $review->user_id;
    }

    public function update(User $user, ReviewReaction $reviewReaction): bool
    {
        return $user->id === $reviewReaction->user_id;
    }

    public function delete(User $user, ReviewReaction $reviewReaction): bool
    {
        return $user->id === $reviewReaction->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReviewReaction $reviewReaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReviewReaction $reviewReaction): bool
    {
        return false;
    }
}
