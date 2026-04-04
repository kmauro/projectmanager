<?php

namespace App\Repositories;

use App\Models\Need;

class NeedRepository
{
    public function getNeeds()
    {
        return Need::all();    
    }

    public function getPredefinedNeeds()
    {
        return Need::all()->where('predefined', true);
    }

    public static function saveOtherNeed($otherNeed)
    {
        $otherNeedId = Need::insertGetId([
            'name' => $otherNeed,
            'predefined' => false,
            'parent_need_id' => null
        ]);
        return $otherNeedId;
    }

    public static function saveOtherEquipment($otherEquipment)
    {
        $otherEquipmentId = Need::insertGetId([
            'name' => $otherEquipment,
            'predefined' => false,
            'parent_need_id' => 3
        ]);
        return $otherEquipmentId;
    }

    
}