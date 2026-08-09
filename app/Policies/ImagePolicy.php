<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ImagePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Image $image): bool
    {
        return $image->users()
            ->where('users.id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Image $image): bool
    {
        return $image->users()
            ->where('users.id', $user->id)
            ->exists();
    }
}
