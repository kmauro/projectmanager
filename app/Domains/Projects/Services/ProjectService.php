<?php

namespace App\Domains\Projects\Services;

use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Repositories\AreaRepository;
use App\Domains\Projects\Repositories\ProjectRepository;
use App\Domains\Projects\Repositories\ProvinceRepository;
use App\Domains\Projects\Repositories\TRLLevelRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProvinceRepository $provinceRepository,
        private AreaRepository $areaRepository,
        private TRLLevelRepository $trlLevelRepository,
    ) {}

    /**
     * @param  array{search: string|null, field: string, per_page: int}  $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        return $this->projectRepository->paginate($filters);
    }

    public function getProject(Project $project): Project
    {
        return $this->projectRepository->loadForDisplay($project);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Project $project, array $data): Project
    {
        return $this->projectRepository->update($project, $data);
    }

    public function delete(Project $project): void
    {
        $this->projectRepository->delete($project);
    }

    public function getProvinces(): Collection
    {
        return $this->provinceRepository->getProvinces();
    }

    public function getAreas(): Collection
    {
        return $this->areaRepository->getAreas();
    }

    public function getTrlLevels(): Collection
    {
        return $this->trlLevelRepository->getTRLLevels();
    }
}
