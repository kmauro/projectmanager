<?php

namespace App\Domains\Files\Services\Strategies;

use App\Domains\Projects\Repositories\NeedRepository;

class NeedStrategy implements MatchInterface
{
    public function matchId($selectedNeeds, $otherNeeds, $predefinedNeeds)
    {
        //combino las predefinidas para buscar las ids de una sola pasada
        $projectSelectedNeeds = $selectedNeeds['selectedNeeds'] . ' ' . $selectedNeeds['selectedEquipment']. ' ' . $selectedNeeds['selectedRights'];
        $ids = $this->matchPredefinedNeeds($projectSelectedNeeds, $predefinedNeeds);

        //chequeo si tiene necesidades especificadas por el usuario
        if($this->hasOtherNeed($projectSelectedNeeds, $otherNeeds['other_need'])){
            $otherNeedId = NeedRepository::saveOtherNeed($otherNeeds['other_need']);
            $ids[] = $otherNeedId;
        }

        //chequeo si tiene equipamientos especificadas por el usuario
        if($this->hasOtherEquipment($projectSelectedNeeds, $otherNeeds['other_equipment'])){
            $otherEquipmentId = NeedRepository::saveOtherEquipment($otherNeeds['other_equipment']);
            $ids[] = $otherEquipmentId;
        }
        return $ids;

    }


    private function matchPredefinedNeeds($selectedNeeds, $predefinedNeeds){
        $ids = [];
        foreach($predefinedNeeds as $need){
            if(str_contains($selectedNeeds, $need->name)){
                $ids[] = $need->id;
            }
        }
        return $ids;
    }

    private function hasOtherNeed($selectedNeeds, $otherNeeds){
        return str_contains($selectedNeeds, 'Otro') && !empty($otherNeeds);
    }

    private function hasOtherEquipment($selectedNeeds, $otherEquipment){
        return str_contains($selectedNeeds, 'Otro') && !empty($otherEquipment);
    }
}
