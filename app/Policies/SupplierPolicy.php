<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use App\Policies\Traits\AccessLevelChecks;
use Illuminate\Auth\Access\Response;

class SupplierPolicy
{

    use AccessLevelChecks;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Supplier $supplier): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Supplier $supplier): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Supplier $supplier): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Supplier $supplier): bool
    {
        return $this->hasAccessLevel($user, [1, 2, 3]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return $this->hasAccessLevel($user, [1]);
    }
}
