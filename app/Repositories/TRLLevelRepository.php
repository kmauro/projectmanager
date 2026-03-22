<?php

namespace App\Repositories;

use App\Models\TRLLevel;

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