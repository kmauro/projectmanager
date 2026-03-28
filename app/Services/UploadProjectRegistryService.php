<?php

namespace App\Services;

use App\Jobs\UploadTechRegistryJob;
use Illuminate\Support\Carbon;
use App\Services\FileImportService;


use App\Repositories\{ApplicantRepository, FileImportRepository, AreaRepository, TechRepository, ProvinceRepository, InstitutionRepository, TRLLevelRepository, NeedRepository, FinancialSourceRepository, ProjectRepository, SectorRepository};

class UploadProjectRegistryService
{
    //TODO
    private function mapColumnsFromFile($headers)
    {
        foreach($headers as $header){
            switch($header)
            {
                default:
                $header = $header;
            }
        }
        return $headers;
    }

    public function uploadProjectRegistryFile($fileUploaded)
    {
        // Guardamos el archivo manualmente en storage/app/imports
        $directory = storage_path('app/imports');

        //si no existe el directorio se crea
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        //asignacion de nombre y extension
        $extension = $fileUploaded->getClientOriginalExtension() ?: 'csv';
        $filename = uniqid('import_', true).'.'.$extension;

        //guardamos el archivo
        $fileUploaded->move($directory, $filename);
        
        
        // Ruta relativa dentro de storage/app
        $uploadedFilePath = 'imports/'.$filename;

        //llamo al repositorio para guardar el registro de la importacion
        //dispatch
        $importRecord = app(FileImportRepository::class);
        $response = $importRecord->createImportRecord($uploadedFilePath);
        
        
        UploadTechRegistryJob::dispatch($uploadedFilePath);
        
        
        return $response;
        
    }

    public function convertRegistryToCollection($uploadedFilePath)
    {
        //Busqueda y apertura del archivo cargado
        $path = storage_path('app/'.$uploadedFilePath);
        //Excepcion por falta de archivo
        if (!file_exists($path)) {
            throw new \RuntimeException('El archivo de importación no existe: '.$path);
        }
        $handle = fopen($path, 'r');
        //Excepcion por error de apertura de archivo
        if ($handle === false) {
            throw new \RuntimeException('No se pudo abrir el archivo de importación.');
        }


        //Salteo de primeras lineas
        fgetcsv($handle, 0, ','); // fila 1 (Ficha tecnologica)
        fgetcsv($handle, 0, ','); // fila 2 (Submission details)


        //TODO
        $headers = fgetcsv($handle, 0, ','); // fila 3 (encabezados)
        $mappedColumns = $this->mapColumnsFromFile($headers);
        //$mappedColumns['applicant_IP'] => 6,
        //$mappedColumns['applicant_name'] => 60
        

        //Se iteran las filas del archivo y se guardan los datos en una coleccion
        //Normalizando la informacion
        $projectRegistry = [];
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $projectRegistry[] = collect([
                'applicant' => [
                    'IP_address' => FileImportService::dataNormalizer($row[6]),
                    //'IP_address' => $row[$mappedColumns['applicant_IP']],
                    'user' => FileImportService::dataNormalizer($row[8]),
                    'full_name' => FileImportService::dataNormalizer($row[60]),
                    'email' => FileImportService::dataNormalizer($row[61]),
                    'phone' => FileImportService::dataNormalizer($row[62]),
                    'cuil' => FileImportService::dataNormalizer($row[63]),
                    'institution_id' => $row[64],
                    'company_name' => FileImportService::dataNormalizer($row[65]),
                    'company_CUIT' => FileImportService::dataNormalizer($row[66]),
                ],
                'project' => [
                    'SID' => FileImportService::dataNormalizer($row[1]),
                    'title' => FileImportService::dataNormalizer($row[9]),
                    'time_sent' => Carbon::createFromFormat('d/m/Y - H:i', $row[2])->format('Y-m-d H:i:s'),
                    'time_completed' => Carbon::createFromFormat('d/m/Y - H:i', $row[3])->format('Y-m-d H:i:s'),
                    'time_modified' => Carbon::createFromFormat('d/m/Y - H:i', $row[4])->format('Y-m-d H:i:s'),
                    'start_date' => Carbon::createFromFormat('d/m/Y', $row[10])->format('Y-m-d'),
                    'end_date' => $row[11] == '' ? null : Carbon::createFromFormat('d/m/Y', $row[11])->format('Y-m-d'),
                    'objective' => FileImportService::dataNormalizer($row[12]),
                    'innovation_value' => FileImportService::dataNormalizer($row[13]),
                    'trl_level_id' => FileImportService::dataNormalizer($row[14]),
                    'area_id' => FileImportService::dataNormalizer($row[15]),
                    'located_entity' => FileImportService::dataNormalizer($row[67]),
                    'province_id' => FileImportService::dataNormalizer($row[68]),
                    'EBCT_amount' => FileImportService::dataNormalizer($row[59]),
                    'selectedTechs' => FileImportService::dataNormalizer($row[16]),
                    'other_tech' => FileImportService::dataNormalizer($row[17])
                ],
            ]);

        }

        //cierre de archivo
        fclose($handle);
        // Eliminamos el archivo después de procesarlo
        unlink($path);

        //Se envia la coleccion al proximo metodo para normalizar la informacion
        //y guardarlo en la base de datos
        
        return collect($projectRegistry);

    }
    

    public function uploadProjectRegistry($projectsRegistries)
    {
        
        //Consigo los repositorios
        $provinces = app(ProvinceRepository::class);
        $institutions = app(InstitutionRepository::class);
        $trl_levels = app(TRLLevelRepository::class);
        $areas = app(AreaRepository::class);
        $techs = app(TechRepository::class);
        $needs = app(NeedRepository::class);
        $financialSources = app(FinancialSourceRepository::class);
        $sectors = app(SectorRepository::class);

        //Precargo los datos predefinidos
        $provinces = $provinces->getProvincesIndexedByName();
        $institutions = $institutions->getInstitutionsIndexedByName();
        $trl_levels = $trl_levels->getTRLLevelsIndexedByName();
        $areas = $areas->getAreasIndexedByName();
        $techs = $techs->getPredefinedTechs();
        $needs = $needs->getNeeds();
        $financialSources = $financialSources->getFinancialSources();
        $sectors = $sectors->getSectors();

        //repositorios para cargar en la bd
        $applicantRepo = app(ApplicantRepository::class);
        $projectRepo = app(ProjectRepository::class);


        //servicio para conseguir ids
        $importService = app(FileImportService::class);

        foreach($projectsRegistries as $projectRegistry)
        {
            
            //se hace esto para evitar tantos atributos | ejemplo:
            //$projectRegistry['project']['SID']
            //$project['SID']
            $project = $projectRegistry['project'];
            $applicant = $projectRegistry['applicant'];

            //Guardo el id de la institucion en vez del nombre
            $applicant['institution_id'] = $institutions[$applicant['institution_id']] ?? 1;

            //guardamos la info del aplicante y le asignamos el id al proyecto
            $project['applicant_id'] = $applicantRepo->insertGetId($applicant);

            //consigo las ids de las fk
            $project['province_id'] = $provinces[$project['province_id']] ?? 1;
            $project['area_id'] = $areas[$project['area_id']] ?? 1;
            $project['trl_level_id'] = $trl_levels[$project['trl_level_id']] ?? 1;

            //Guardamos la info del proyecto 
            $projectId = $projectRepo->insertGetId($project);

            //conseguimos las ids de las tecnologias que utiliza el proyecto
            $techIds = $importService->getSelectedTechs($project['selectedTechs'], $project['other_tech'], $techs);

            //Guardamos la relacion entre proyecto y tecnologias
            $projectRepo->insertProjectTechs($projectId, $techIds);

            //needs
            //financial sources
            //sectors




            
        }

    }
   
}
