<?php

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Models\ProjectFundingSource;
use App\Domains\Projects\Models\ProjectNeed;
use App\Domains\Projects\Models\ProjectSector;
use App\Domains\Projects\Models\ProjectTech;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProjectRepository
{
    /**
     * @param  array{search: string|null, field: string, per_page: int}  $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $projects = Project::query()
            ->select(['id', 'SID', 'title', 'applicant_id', 'trl_level_id', 'province_id'])
            ->with([
                'applicant:id,full_name',
                'trlLevel:id,trl_level',
                'province:id,province',
            ]);

        $this->applySearch($projects, $filters['search'], $filters['field']);

        return $projects
            ->latest('id')
            ->paginate($filters['per_page'])
            ->withQueryString();
    }

    public function loadForDisplay(Project $project): Project
    {
        return $project->load([
            'applicant:id,full_name,email,phone',
            'area:id,area',
            'province:id,province',
            'trlLevel:id,trl_level',
            'techs:id,name',
            'needs:id,name',
            'fundingSources:id,name,type',
            'sectors:id,name',
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Project $project, array $data): Project
    {
        $project->update($data);

        return $project->refresh();
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }

    /**
     * @param  array<string, mixed>  $project
     */
    public function insertGetId(array $project): int
    {
        return Project::query()->insertGetId([
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
            'EBCT_amount' => $project['EBCT_amount'],
            'countries' => $project['countries'],
        ]);
    }

    /**
     * @param  array<int, int>  $techIds
     */
    public function insertProjectTechs(int $projectId, array $techIds): void
    {
        $data = array_map(fn (int $id): array => [
            'project_id' => $projectId,
            'tech_id' => $id,
        ], $techIds);

        if ($data !== []) {
            ProjectTech::query()->insert($data);
        }
    }

    /**
     * @param  array<int, int>  $needIds
     */
    public function insertProjectNeeds(int $projectId, array $needIds): void
    {
        $data = array_map(fn (int $id): array => [
            'project_id' => $projectId,
            'need_id' => $id,
        ], $needIds);

        if ($data !== []) {
            ProjectNeed::query()->insert($data);
        }
    }

    /**
     * @param  array<int, array{source_id: int, percentage: float}>  $fundingSources
     */
    public function insertProjectFinancialSources(int $projectId, array $fundingSources): void
    {
        $data = [];

        foreach ($fundingSources as $fundingSource) {
            $data[$fundingSource['source_id']] = [
                'project_id' => $projectId,
                'source_id' => $fundingSource['source_id'],
                'percentage' => $fundingSource['percentage'],
            ];
        }

        if ($data !== []) {
            ProjectFundingSource::query()->insert(array_values($data));
        }
    }

    /**
     * @param  array<int, int>  $sectorIds
     */
    public function insertProjectSectors(int $projectId, array $sectorIds): void
    {
        $sectorIds = array_values(array_unique($sectorIds));
        $data = array_map(fn (int $id): array => [
            'project_id' => $projectId,
            'sector_id' => $id,
        ], $sectorIds);

        if ($data !== []) {
            ProjectSector::query()->insert($data);
        }
    }

    private function applySearch(Builder $projects, ?string $search, string $field): void
    {
        if ($search === null || trim($search) === '') {
            return;
        }

        $term = '%'.trim($search).'%';

        match ($field) {
            'applicant' => $projects->whereHas('applicant', fn (Builder $query) => $query->where('full_name', 'like', $term)),
            'trl' => $projects->whereHas('trlLevel', fn (Builder $query) => $query->where('trl_level', 'like', $term)),
            'province' => $projects->whereHas('province', fn (Builder $query) => $query->where('province', 'like', $term)),
            default => $projects->where('title', 'like', $term),
        };
    }
}
