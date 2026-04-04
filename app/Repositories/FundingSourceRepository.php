<?php

namespace App\Repositories;

use App\Models\FundingSource;

class FundingSourceRepository
{
    public function getFinancialSources()
    {
        return FundingSource::all();
    }
}