<?php

namespace App\Domains\Files\Services;

use App\Domains\Projects\Repositories\{
    ApplicantRepository,
    AreaRepository,
    FundingSourceRepository,
    InstitutionRepository,
    NeedRepository,
    ProjectRepository,
    ProvinceRepository,
    SectorRepository,
    TechRepository,
    TRLLevelRepository,
};

use App\Domains\Files\Services\Strategies\{
    FundingSourceStrategy,
    MatcherContext,
    TechStrategy,
    NeedStrategy
};

use Illuminate\Support\Facades\DB;

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
        private NeedRepository $needRepo,
        private SectorRepository $sectorRepo,
        private SectorMatcher $sectorMatcher,
        private FundingSourceRepository $fundingSourceRepo,
    ) {}

    /**
     * Receives the Collection from the Parser already normalized and structured, resolves the ids
     * for the string fields such as provinces, institutions, etc. and matches the ids for the
     * other fields (techs, needs, etc.)
     */
    public function persistToDatabase(\Illuminate\Support\Collection $projectRegistry): void
    {
        DB::transaction(function () use ($projectRegistry) {

            $provinces = $this->provinceRepo->getProvincesIndexedByName();
            $institutions = $this->institutionRepo->getInstitutionsIndexedByName();
            $trlLevels = $this->trlLevelRepo->getTRLLevelsIndexedByName();
            $areas = $this->areaRepo->getAreasIndexedByName();
            $techs = $this->techRepo->getPredefinedTechs();
            $needs = $this->needRepo->getPredefinedNeeds();
            $sectors = $this->sectorRepo->getSectorsForMatching();
            $fundingSources = $this->fundingSourceRepo->getFundingSourcesForMatching();

            $strategyContext = app(MatcherContext::class);
            $fundingSourceStrategy = app(FundingSourceStrategy::class);

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
                $project['area_id'] = $this->resolveId($areas, $project['area_id'], 'Area');
                $project['trl_level_id'] = $this->resolveId($trlLevels, $project['trl_level_id'], 'Nivel TRL');

                // Insert project
                $projectId = $this->projectRepo->insertGetId($project);

                //STRATEGY

                // Resolve and insert project-tech relationship
                $strategyContext->setStrategy(app(TechStrategy::class));
                $techIds = $strategyContext->handle($project['selectedTechs'], $project['other_tech'], $techs, $projectId);
                $this->projectRepo->insertProjectTechs($projectId, $techIds);

                //Resolve and insert project-need relationship
                $strategyContext->setStrategy(app(NeedStrategy::class));
                $needIds = $strategyContext->handle($project['needs'], $project['other_needs'], $needs, $projectId);
                $this->projectRepo->insertProjectNeeds($projectId, $needIds);

                $sectorIds = $this->sectorMatcher->matchIds($project['sectors'], $sectors);
                $this->projectRepo->insertProjectSectors($projectId, $sectorIds);

                $strategyContext->setStrategy($fundingSourceStrategy);
                $matchedFundingSources = $strategyContext->handle($project['funding_sources'], null, $fundingSources, $projectId);
                $this->projectRepo->insertProjectFinancialSources($projectId, $matchedFundingSources);
            }
        });
    }



    /**
     * It gets the id of the record in the database so we can later filter by id instead
     * of filtering by the string
     */
    private function resolveId($map, string $name, string $entityName): int
    {
        if (!isset($map[$name])) {
            throw new \RuntimeException("El registro de {$entityName} '{$name}' no existe en la base de datos.");
        }
        return $map[$name];
    }
}
