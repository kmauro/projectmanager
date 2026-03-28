<?php

namespace App\Services;

use App\Repositories\{
    ApplicantRepository,
    ProjectRepository,
    ProvinceRepository,
    InstitutionRepository,
    TRLLevelRepository,
    AreaRepository,
    TechRepository,
    NeedRepository,
    FinancialSourceRepository,
    SectorRepository
};

class ProjectPersistenceService
{
    public function __construct(
        private ApplicantRepository $applicantRepo,
        private ProjectRepository $projectRepo,
        private ProvinceRepository $provinceRepo,
        private InstitutionRepository $institutionRepo,
        private TRLLevelRepository $trlLevelRepo,
        private AreaRepository $areaRepo,
        private TechRepository $techRepo,
        private TechMatcher $techMatcher
    ) {}

    public function persistToDatabase(\Illuminate\Support\Collection $projectRegistry): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($projectRegistry) {
            $provinces = $this->provinceRepo->getProvincesIndexedByName();
            $institutions = $this->institutionRepo->getInstitutionsIndexedByName();
            $trlLevels = $this->trlLevelRepo->getTRLLevelsIndexedByName();
            $areas = $this->areaRepo->getAreasIndexedByName();
            $techs = $this->techRepo->getPredefinedTechs();

            foreach ($projectRegistry as $record) {
                $project = $record['project'];
                $applicant = $record['applicant'];

                // Resolve institution name to ID
                if (!isset($institutions[$applicant['institution_id']])) {
                    throw new \RuntimeException("La institución '{$applicant['institution_id']}' no existe en la base de datos.");
                }
                $applicant['institution_id'] = $institutions[$applicant['institution_id']];

                // Insert applicant and get ID
                $project['applicant_id'] = $this->applicantRepo->insertGetId($applicant);

                // Resolve FK names to IDs
                $project['province_id'] = $this->resolveId($provinces, $project['province_id'], 'Provincia');
                $project['area_id'] = $this->resolveId($areas, $project['area_id'], 'Área');
                $project['trl_level_id'] = $this->resolveId($trlLevels, $project['trl_level_id'], 'Nivel TRL');

                // Insert project
                $projectId = $this->projectRepo->insertGetId($project);

                // Resolve and insert project-tech relationship
                $techIds = $this->techMatcher->resolveTechIds($project['selectedTechs'], $project['other_tech'], $techs);
                $this->projectRepo->insertProjectTechs($projectId, $techIds);

                // TODO: needs, financial sources, sectors
            }
        });
    }

    private function resolveId(array $map, string $name, string $entityName): int
    {
        if (!isset($map[$name])) {
            throw new \RuntimeException("El registro de {$entityName} '{$name}' no existe en la base de datos.");
        }
        return $map[$name];
    }
}
