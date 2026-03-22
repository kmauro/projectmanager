<?php

namespace App\Repositories;

use App\Models\Need;

class NeedRepository
{
    public function getNeeds()
    {
        return Need::all();    
    }
}