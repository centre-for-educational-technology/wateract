<?php

namespace App\Policies;

use App\Models\Measurement;
use App\Models\Spring;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeasurementPolicy
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
     * @param  \App\Models\Measurement  $measurement
     * @return mixed
     */
    public function view(User $user, Measurement $measurement)
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
        if ( $user->hasRole(['editor', 'admin', 'super-admin']) ) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Measurement  $measurement
     * @return mixed
     */
    public function update(User $user, Measurement $measurement)
    {
        $spring = Spring::find($measurement->spring_id);
        if ( $spring->canEdit() ) {
            return true;
        } else  {
            return $user->id === $measurement->user_id;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Measurement  $measurement
     * @return mixed
     */
    public function delete(User $user, Measurement $measurement)
    {
        $spring = Spring::find($measurement->spring_id);
        if ( $spring->canEdit() ) {
            return true;
        } else {
            return $user->id === $measurement->user_id;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Measurement  $measurement
     * @return mixed
     */
    public function restore(User $user, Measurement $measurement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Measurement  $measurement
     * @return mixed
     */
    public function forceDelete(User $user, Measurement $measurement)
    {
        //
    }
}
