<?php

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Models\Sector;
use Illuminate\Database\Eloquent\Collection;

class SectorRepository
{
    public function getSectors(): Collection
    {
        return Sector::all();
    }

    public function getSectorsForMatching(): Collection
    {
        return Sector::query()
            ->select(['id', 'name', 'parent_sector_id'])
            ->orderBy('id')
            ->get();
    }
}
