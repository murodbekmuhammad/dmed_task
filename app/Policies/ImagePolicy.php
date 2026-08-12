<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @class ImagePolicy
 *
 * @package App\Policies
 */
class ImagePolicy
{
    /**
     * view
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Image $image
     * @return bool
     */
    public function view(User $user, Image $image): bool
    {
        return $user->images()->where('image_id', $image->id)->exists();
    }

    /**
     * delete
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Image $image
     * @return bool
     */
    public function delete(User $user, Image $image): bool
    {
        return $user->images()->where('image_id', $image->id)->exists();
    }
}
