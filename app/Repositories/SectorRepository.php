<?php

namespace App\Repositories;

use App\Models\Sector;

class SectorRepository
{
    public function getSectors(){
        return Sector::all();
    }    
}