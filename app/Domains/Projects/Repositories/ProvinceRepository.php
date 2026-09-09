<?php

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Models\Province;

class ProvinceRepository
{
    public function getProvinces(){
        return Province::all();
    }

    public function getProvincesIndexedByName()
    {
        return Province::pluck('id', 'province');
    }
}
