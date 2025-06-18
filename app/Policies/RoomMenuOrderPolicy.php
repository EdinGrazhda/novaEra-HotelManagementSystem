<?php

namespace App\Policies;

use App\Models\RoomMenuOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomMenuOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('menuView');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RoomMenuOrder $roomMenuOrder): bool
    {
        return $user->hasPermissionTo('menuView');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('menuCreateOrder');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RoomMenuOrder $roomMenuOrder): bool
    {
        return $user->hasPermissionTo('menuOrderEdit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RoomMenuOrder $roomMenuOrder): bool
    {
        return $user->hasPermissionTo('menuOrderEdit');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RoomMenuOrder $roomMenuOrder): bool
    {
        return $user->hasPermissionTo('menuOrderEdit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RoomMenuOrder $roomMenuOrder): bool
    {
        return $user->hasPermissionTo('menuOrderEdit');
    }
}
