<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Policies\Traits\AccessLevelChecks;

class UserPolicy
{
    /** 
     * Listado ids de access_level:
     * 1- SysAdmin
     * 2- Gerente
     * 3- Administración
     * 4- Encargado
     * 5- Vendedor
     * 6- Contador
     */

    use AccessLevelChecks;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $this->hasAccessLevel($user, [1, 2]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $this->hasAccessLevel($user, [1]);
    }
}
