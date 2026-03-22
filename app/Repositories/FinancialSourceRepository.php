<?php

namespace App\Repositories;

use App\Models\FinancialSource;

class FinancialSourceRepository
{
    public function getFinancialSources()
    {
        return FinancialSource::all();
    }
}