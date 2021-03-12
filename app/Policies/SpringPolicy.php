<?php

namespace App\Policies;

use App\Models\Spring;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpringPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spring  $spring
     * @return mixed
     */
    public function view(User $user, Spring $spring)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spring  $spring
     * @return mixed
     */
    public function update(User $user, Spring $spring)
    {
        if ( $spring->canEdit() ) {
            return true;
        } else if ( $spring->status === 'draft' ) {
            return $user->id === $spring->user_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spring  $spring
     * @return mixed
     */
    public function delete(User $user, Spring $spring)
    {
        if ( $spring->canEdit() ) {
            return true;
        } else if ( $spring->status === 'draft' ) {
            return $user->id === $spring->user_id;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spring  $spring
     * @return mixed
     */
    public function restore(User $user, Spring $spring)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spring  $spring
     * @return mixed
     */
    public function forceDelete(User $user, Spring $spring)
    {
        //
    }
}
