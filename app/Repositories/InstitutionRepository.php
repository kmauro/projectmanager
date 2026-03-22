<?php

namespace App\Repositories;

use App\Models\Institution;

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