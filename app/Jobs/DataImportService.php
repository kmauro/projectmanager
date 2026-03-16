<?php

namespace App\Jobs;

use App\Http\Controllers\ImportController;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\{Area, FinancialSource, Institution, Need, Province, Sector, Tech, TRLLevel};

class DataImportService implements ShouldQueue
{
    use Queueable;

    protected $import;
    /**
     * Create a new job instance.
     */
    public function __construct($import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //Busqueda y apertura del archivo cargado
        $path = storage_path('app/'.$this->import->file_path);

        //Excepcion por falta de archivo
        if (!file_exists($path)) {
            throw new \RuntimeException('El archivo de importación no existe: '.$path);
        }

        $handle = fopen($path, 'r');

        //Excepcion por error de apertura de archivo
        if ($handle === false) {
            throw new \RuntimeException('No se pudo abrir el archivo de importación.');
        }

        //Precargo los datos de las tablas auxiliares
        $provinces = Province::pluck('id', 'province');
        $institutions = Institution::pluck('id', 'institution');
        $trl_levels = TRLLevel::pluck('id', 'trl_level');
        $areas = Area::pluck('id', 'area');
        $techs = Tech::all();
        $needs = Need::all();
        $financialSources = FinancialSource::all();
        $sectors = Sector::all();


        //Salteo de primeras lineas
        fgetcsv($handle, 0, ','); // fila 1 (Ficha tecnologica)
        fgetcsv($handle, 0, ','); // fila 2 (Submission details)
        fgetcsv($handle, 0, ','); // fila 3 (encabezados)

        
        //Se guardan los datos especificos del solicitante y se relaciona con los datos
        //del proyecto.
        while (($row = fgetcsv($handle, 0, ',')) !== false) {

            
            DB::transaction(function() use ($row, $provinces, $institutions, $trl_levels, $areas, $techs, $needs, $financialSources, $sectors){
                /* 
                ImportController::dataNormalizer(): Limpia los datos del CSV para poder
                guardarlos correctamente

                Carbon::createFromFormat(): Modifica los campos del CSV que contienen
                fechas y/o horas para poder guardarlos en el formato de SQL
                */
                $applicantID = DB::table('applicants')->insertGetId([
                    'IP_address' => $row[6],
                    'user' => $row[8],
                    'full_name' => $row[60],
                    'email' => $row[61],
                    'phone' => $row[62],
                    'cuil' => $row[63],
                    'institution_id' => $institutions[ImportController::dataNormalizer($row[64])] ?? null,
                    'company_name' => $row[65],
                    'company_CUIT' => $row[66],
                ]);
                
                $projectID = DB::table('projects')->insertGetId([
                    'SID' => $row[1],
                    'title' => $row[9],
                    'time_sent' => Carbon::createFromFormat('d/m/Y - H:i', $row[2])->format('Y-m-d H:i:s'),
                    'time_completed' => Carbon::createFromFormat('d/m/Y - H:i', $row[3])->format('Y-m-d H:i:s'),
                    'time_modified' => Carbon::createFromFormat('d/m/Y - H:i', $row[4])->format('Y-m-d H:i:s'),
                    'applicant_id' => $applicantID,
                    'start_date' => Carbon::createFromFormat('d/m/Y', $row[10])->format('Y-m-d'),
                    'end_date' => $row[11] == '' ? null : Carbon::createFromFormat('d/m/Y', $row[11])->format('Y-m-d'),
                    'objective' => $row[12],
                    'innovation_value' => $row[13],
                    'trl_level_id' => $trl_levels[ImportController::dataNormalizer($row[14])] ?? null, //TODO
                    'area_id' => $areas[ImportController::dataNormalizer($row[15])] ?? null, //TODO
                    'located_entity' => $row[67],
                    'province_id' => $provinces[ImportController::dataNormalizer($row[68])] ?? null, //TODO
                    'EBCT_amount' => $row[59]
                ]);

                //RELACIONES MUCHOS A MUCHOS
                //tech relation


                $techIds = ImportController::dataCheck($row[16], $techs);

                foreach($techIds as $techID){
                    DB::table('projects_techs')->insert([
                        'project_id' => $projectID,
                        'tech_id' => $techID,
                        'other_tech' => $row[17]
                    ]);
                }

                //needs relation
                /*
                $row[69] = Necesidades principales
                $row[70] = Detalle de equipamiento => ID base de datos para equipamiento = 3
                $row[71] = Especificacion de otro equipamiento => ID base de datos para otro equipamiento = 22
                $row[72] = Detalle de derecho intelectual => ID base de datos para derecho intelectual = 28
                $row[73] = Especificacion de 3 paises => ID base de datos para paises = 37 | comercio exterior (padre) = 36
                $row[74] = Especificacion de otras necesidades => ID base de datos para otras necesidades = 38
                */

                $needIds = ImportController::dataCheck($row[69], $needs);
                if(in_array(3, $needIds)){ //Si marcó equipamiento, buscamos mas ids
                    $equipmentIds = ImportController::dataCheck($row[70], $needs);
                    $needIds = array_unique(array_merge($needIds, $equipmentIds));
                }
                if(in_array(28, $needIds)){
                    $intelectualIds = ImportController::dataCheck($row[72], $needs);
                    $needIds = array_unique(array_merge($needIds, $intelectualIds));
                }

                
                foreach($needIds as $needId){
                    DB::table('projects_needs')->insert([
                        'project_id' => $projectID,
                        'need_id' => $needId,
                        'other_equipment' => $row[71],
                        'other_need' => $row[74],
                        'countries' => $row[73]
                    ]);
                }




            });
        }

        //cierre de archivo
        fclose($handle);

        // Eliminamos el archivo después de procesarlo
        unlink($path);
        
    }
}
