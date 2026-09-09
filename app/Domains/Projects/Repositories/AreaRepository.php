<?php

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Models\Area;

class AreaRepository
{
    public function getAreas()
    {
        return Area::all();
    }

    public function getAreasIndexedByName()
    {
        return Area::pluck('id', 'area');
    }
}
