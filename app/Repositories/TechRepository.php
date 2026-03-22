<?php

namespace App\Repositories;

use App\Models\Tech;


class TechRepository
{
    public function getPredefinedTechs()
    {
        return Tech::all()->where('predefined', true);
    }


    public static function saveOtherTech($otherTech)
    {
        $otherTechId = Tech::insertGetId([
            'name' => $otherTech,
            'predefined' => false
        ]);
        return $otherTechId;
    }

    
}