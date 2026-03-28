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

    public function insertProjectTechs($projectId, array $techIds)
    {
        $data = array_map(fn($id) => [
            'project_id' => $projectId,
            'tech_id' => $id
        ], $techIds);

        if (!empty($data)) {
            ProjectTech::insert($data);
        }
    }

    public function insertProjectNeeds($projectId, array $needIds)
    {
        $data = array_map(fn($id) => [
            'project_id' => $projectId,
            'need_id' => $id
        ], $needIds);

        if (!empty($data)) {
            ProjectNeed::insert($data);
        }
    }

    public function insertProjectFinancialSources($projectId, array $sourceIds)
    {
        $data = array_map(fn($id) => [
            'project_id' => $projectId,
            'financial_source_id' => $id
        ], $sourceIds);

        if (!empty($data)) {
            ProjectFinancialSource::insert($data);
        }
    }

    public function insertProjectSectors($projectId, array $sectorIds)
    {
        $data = array_map(fn($id) => [
            'project_id' => $projectId,
            'sector_id' => $id
        ], $sectorIds);

        if (!empty($data)) {
            ProjectSector::insert($data);
        }
    }
}