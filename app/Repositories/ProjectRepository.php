<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\ProjectFinancialSource;
use App\Models\ProjectNeed;
use App\Models\ProjectSector;
use App\Models\ProjectTech;

class ProjectRepository
{

    public static function getProjectsPaginated($quantity)
    {
        return Project::paginate($quantity);
    }

    public static function getProject($id)
    {
        $project =  Project::find($id);
        if(empty($project)){
            abort(404);
        }
        $applicant = ApplicantRepository::getApplicant($project->applicant_id);

        return collect(['project' => $project, 'applicant' => $applicant]);
    }


    public function insertGetId($project)
    {
        $projectId = Project::insertGetId([
            'SID' => $project['SID'],
            'title' => $project['title'],
            'time_sent' => $project['time_sent'],
            'time_completed' => $project['time_completed'],
            'time_modified' => $project['time_modified'],
            'applicant_id' => $project['applicant_id'],
            'start_date' => $project['start_date'],
            'end_date' => $project['end_date'],
            'objective' => $project['objective'],
            'innovation_value' => $project['innovation_value'],
            'trl_level_id' => $project['trl_level_id'], 
            'area_id' => $project['area_id'],
            'located_entity' => $project['located_entity'],
            'province_id' => $project['province_id'],
            'EBCT_amount' => $project['EBCT_amount']
        ]);
        return $projectId ?? null;
    }

    public function insertProjectTechs($projectId, $techIds)
    {
        foreach($techIds as $techId){
            ProjectTech::insert([
                'project_id' => $projectId,
                'tech_id' => $techId
                
            ]);
        }
    }

    public function insertProjectNeeds($projectId, $needIds)
    {
        foreach($needIds as $needId){
            ProjectNeed::insert([
                'project_id' => $projectId,
                'need_id' => $needId
            ]);
        }
      /*foreach($needIds as $needId){
            ProjectNeed::insert([
                'project_id' => $projectId,
                'need_id' => $needId,
                'other_equipment' => $row[71],
                'other_need' => $row[74],
                'countries' => $row[73]
            ]);
        }*/
    }

    public function insertProjectFinancialSources($projectId, $sourceIds)
    {
        foreach($sourceIds as $sourceId){
            ProjectFinancialSource::insert([
                'project_id' => $projectId,
                'financial_source_id' => $sourceId
            ]);
        }
    }

    public function insertProjectSectors($projectId, $sectorIds)
    {
        foreach($sectorIds as $sectorId){
            ProjectSector::insert([
                'project_id' => $projectId,
                'sector_id' => $sectorId
            ]);
        }
    }
}