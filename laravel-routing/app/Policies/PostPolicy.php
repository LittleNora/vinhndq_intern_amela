<?php

namespace App\Policies;

use App\Models\Administrators;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{

    public function before($user, $ability)
    {
        return !(get_class($user) != Administrators::class);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User|Administrators $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User|Administrators $user, Post $post): bool
    {
        //
        return $user->id == $post->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User|Administrators $user): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|Administrators $user, Post $post): bool
    {
        //
        return $user->id == $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User|Administrators $user, Post $post): bool
    {
        //
        return $user->id == $post->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User|Administrators $user, Post $post): bool
    {
        //
        return $user->id == $post->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User|Administrators $user, Post $post): bool
    {
        //
        return $user->id == $post->user_id;
    }
}
