<?php

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Models\TRLLevel;

class TRLLevelRepository
{
    public function getTRLLevels()
    {
        return TRLLevel::all();
    }

    public function getTRLLevelsIndexedByName()
    {
        return TRLLevel::pluck('id', 'trl_level');
    }
}
