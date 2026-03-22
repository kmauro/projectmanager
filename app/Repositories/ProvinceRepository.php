<?php

namespace App\Repositories;

use App\Models\Province;

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