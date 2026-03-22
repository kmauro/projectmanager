<?php

namespace App\Services;

use App\Repositories\FileImportRepository;
use App\Repositories\TechRepository;


class FileImportService
{
    public static function dataNormalizer($fileData)
    {
        $normalizedFileData = $fileData;
        $normalizedFileData = trim($normalizedFileData);
        $normalizedFileData = str_replace("\t", " ", $normalizedFileData);
        $normalizedFileData = preg_replace('/\s+/', ' ', $normalizedFileData);
        return $normalizedFileData;
    }

    public static function dataCheck($fileData, $dbData) :array
    {
        $ids = [];
        foreach($dbData as $record){
            if(str_contains($fileData, $record->name)){
                $ids[] = $record->id;
            }
        }
        return $ids;
    }

    public function existsOtherTech($selectedTechs, $otherTech)
    {
        return str_contains($selectedTechs, 'Otro') && !empty($otherTech);
        
    }

    private function searchPredefinedTechs($selectedTechs, $predefinedTechs) :array
    {
        $ids = [];
        foreach($predefinedTechs as $predefinedTech){
            
            if(str_contains($selectedTechs, $predefinedTech->name)){
                $ids[] = $predefinedTech->id;
            }
        }
        return $ids;
    }

    public function getSelectedTechs($selectedTechs, $otherTech, $predefinedTechs) :array
    {
        $ids = $this->searchPredefinedTechs($selectedTechs, $predefinedTechs);

        if($this->existsOtherTech($selectedTechs, $otherTech)){            
            $otherID = TechRepository::saveOtherTech($otherTech);
            $ids[] = $otherID;
        }
        return $ids;
    }

    public function getNeeds($row, $needs, $projectId){
        //needs relation
                /*
                $row[69] = Necesidades principales
                $row[70] = Detalle de equipamiento => ID base de datos para equipamiento = 3
                $row[71] = Especificacion de otro equipamiento => ID base de datos para otro equipamiento = 22
                $row[72] = Detalle de derecho intelectual => ID base de datos para derecho intelectual = 28
                $row[73] = Especificacion de 3 paises => ID base de datos para paises = 37 | comercio exterior (padre) = 36
                $row[74] = Especificacion de otras necesidades => ID base de datos para otras necesidades = 38
                */

                $needIds = $this->dataCheck($row[69], $needs);
                if(in_array(3, $needIds)){ //Si marcó equipamiento, buscamos mas ids
                    $equipmentIds = $this->dataCheck($row[70], $needs);
                    $needIds = array_unique(array_merge($needIds, $equipmentIds));
                }
                if(in_array(28, $needIds)){
                    $intelectualIds = $this->dataCheck($row[72], $needs);
                    $needIds = array_unique(array_merge($needIds, $intelectualIds));
                }
                return $needIds;
                
    }

    
}