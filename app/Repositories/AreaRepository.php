<?php

namespace App\Repositories;

use App\Models\Area;

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