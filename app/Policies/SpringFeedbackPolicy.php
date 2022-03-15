<?php

namespace App\Policies;

use App\Models\Spring;
use App\Models\SpringFeedback;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpringFeedbackPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpringFeedback  $springFeedback
     * @return mixed
     */
    public function view(User $user, SpringFeedback $springFeedback)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpringFeedback  $springFeedback
     * @return mixed
     */
    public function update(User $user, SpringFeedback $springFeedback)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpringFeedback  $springFeedback
     * @return mixed
     */
    public function delete(User $user, SpringFeedback $springFeedback)
    {
        $spring = Spring::find($springFeedback->spring_id);
        if ( $spring->canEdit() ) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpringFeedback  $springFeedback
     * @return mixed
     */
    public function restore(User $user, SpringFeedback $springFeedback)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpringFeedback  $springFeedback
     * @return mixed
     */
    public function forceDelete(User $user, SpringFeedback $springFeedback)
    {
        //
    }
}
