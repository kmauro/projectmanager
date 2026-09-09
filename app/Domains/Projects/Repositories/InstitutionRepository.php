<?php

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Models\Institution;

class InstitutionRepository
{
    public function getInstitutions()
    {
        return Institution::all();
    }

    public function getInstitutionsIndexedByName()
    {
        return Institution::pluck('id', 'institution');
    }
}
