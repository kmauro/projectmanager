<?php

namespace App\Policies\Traits;

use Illuminate\Support\Collection;
use App\Models\User;

trait AccessLevelChecks
{
    /**
     * Devuelve la colección de access_level ids definida en config/access_levels.php
     */
    protected function accessLevels(string $role): Collection
    {
        return collect(config("access_levels.{$role}", []));
    }

    /**
     * Comprueba si el usuario tiene alguno de los access_level ids indicados.
     * - $ids puede ser un string (clave de config/access_levels) o un array/Collection de ids.
     */
    protected function hasAccessLevel(User $user, string|array $ids): bool
    {
        $collection = is_string($ids) ? $this->accessLevels($ids) : collect($ids);    
        return $collection->contains($user->access_level_id);
    }
}
