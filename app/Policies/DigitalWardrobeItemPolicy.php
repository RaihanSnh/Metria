<?php

namespace App\Policies;

use App\Models\DigitalWardrobeItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DigitalWardrobeItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DigitalWardrobeItem $digitalWardrobeItem): bool
    {
        return $user->id === $digitalWardrobeItem->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DigitalWardrobeItem $digitalWardrobeItem): bool
    {
        return $user->id === $digitalWardrobeItem->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DigitalWardrobeItem $digitalWardrobeItem): bool
    {
        return $user->id === $digitalWardrobeItem->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DigitalWardrobeItem $digitalWardrobeItem): bool
    {
        return $user->id === $digitalWardrobeItem->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DigitalWardrobeItem $digitalWardrobeItem): bool
    {
        return $user->id === $digitalWardrobeItem->user_id;
    }
}
