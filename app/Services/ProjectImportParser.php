<?php

namespace App\Services;

use Illuminate\Support\Carbon;
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

class ProjectImportParser
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
        private FinancialSourceRepository $financialSourceRepo,
        private SectorRepository $sectorRepo,
        private TechMatcher $techMatcher
    ) {}

    public function parseFile(string $uploadedFilePath): \Illuminate\Support\Collection
    {
        $path = storage_path('app/'.$uploadedFilePath);

        if (!file_exists($path)) {
            throw new \RuntimeException('El archivo de importación no existe: '.$path);
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \RuntimeException('No se pudo abrir el archivo de importación.');
        }

        // Skip header rows
        fgetcsv($handle, 0, ','); // row 1: Ficha tecnologica
        fgetcsv($handle, 0, ','); // row 2: Submission details
        fgetcsv($handle, 0, ','); // row 3: headers

        $projectRegistry = [];

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $projectRegistry[] = collect([
                'applicant' => [
                    'IP_address' => DataNormalizer::normalize($row[6]),
                    'user' => DataNormalizer::normalize($row[8]),
                    'full_name' => DataNormalizer::normalize($row[60]),
                    'email' => DataNormalizer::normalize($row[61]),
                    'phone' => DataNormalizer::normalize($row[62]),
                    'cuil' => DataNormalizer::normalize($row[63]),
                    'institution_id' => $row[64],
                    'company_name' => DataNormalizer::normalize($row[65]),
                    'company_CUIT' => DataNormalizer::normalize($row[66]),
                ],
                'project' => [
                    'SID' => DataNormalizer::normalize($row[1]),
                    'title' => DataNormalizer::normalize($row[9]),
                    'time_sent' => Carbon::createFromFormat('d/m/Y - H:i', $row[2])->format('Y-m-d H:i:s'),
                    'time_completed' => Carbon::createFromFormat('d/m/Y - H:i', $row[3])->format('Y-m-d H:i:s'),
                    'time_modified' => Carbon::createFromFormat('d/m/Y - H:i', $row[4])->format('Y-m-d H:i:s'),
                    'start_date' => Carbon::createFromFormat('d/m/Y', $row[10])->format('Y-m-d'),
                    'end_date' => $row[11] == '' ? null : Carbon::createFromFormat('d/m/Y', $row[11])->format('Y-m-d'),
                    'objective' => DataNormalizer::normalize($row[12]),
                    'innovation_value' => DataNormalizer::normalize($row[13]),
                    'trl_level_id' => DataNormalizer::normalize($row[14]),
                    'area_id' => DataNormalizer::normalize($row[15]),
                    'located_entity' => DataNormalizer::normalize($row[67]),
                    'province_id' => DataNormalizer::normalize($row[68]),
                    'EBCT_amount' => DataNormalizer::normalize($row[59]),
                    'selectedTechs' => DataNormalizer::normalize($row[16]),
                    'other_tech' => DataNormalizer::normalize($row[17]),
                ],
            ]);
        }

        fclose($handle);
        unlink($path);

        return collect($projectRegistry);
    }

    public function persistToDatabase(\Illuminate\Support\Collection $projectRegistry): void
    {
        $provinces = $this->provinceRepo->getProvincesIndexedByName();
        $institutions = $this->institutionRepo->getInstitutionsIndexedByName();
        $trlLevels = $this->trlLevelRepo->getTRLLevelsIndexedByName();
        $areas = $this->areaRepo->getAreasIndexedByName();
        $techs = $this->techRepo->getPredefinedTechs();

        foreach ($projectRegistry as $record) {
            $project = $record['project'];
            $applicant = $record['applicant'];

            // Resolve institution name to ID
            $applicant['institution_id'] = $institutions[$applicant['institution_id']] ?? 1;

            // Insert applicant and get ID
            $project['applicant_id'] = $this->applicantRepo->insertGetId($applicant);

            // Resolve FK names to IDs
            $project['province_id'] = $provinces[$project['province_id']] ?? 1;
            $project['area_id'] = $areas[$project['area_id']] ?? 1;
            $project['trl_level_id'] = $trlLevels[$project['trl_level_id']] ?? 1;

            // Insert project
            $projectId = $this->projectRepo->insertGetId($project);

            // Resolve and insert project-tech relationship
            $techIds = $this->techMatcher->resolveTechIds($project['selectedTechs'], $project['other_tech'], $techs);
            $this->projectRepo->insertProjectTechs($projectId, $techIds);

            // TODO: needs, financial sources, sectors
        }
    }
}
