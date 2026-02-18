<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Policies\Traits\AccessLevelChecks;

class ProductPolicy
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
        return $this->hasAccessLevel($user, [1, 2, 3, 4, 5]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4, 5]);
    }

    public function control(User $user): bool
    {
        // Uso centralizado de access levels definido en config/access_levels.php
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $this->hasAccessLevel($user, [1]);
    }
}
