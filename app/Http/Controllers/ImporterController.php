<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImporterController extends Controller
{
    public static function dataImport(string $path)
    {
        //Busqueda y apertura del archivo cargado
        $fullPath = storage_path('app/'.$path);

        if (!file_exists($fullPath)) {
            throw new \RuntimeException('El archivo de importación no existe: '.$fullPath);
        }

        $handle = fopen($fullPath, 'r');

        if ($handle === false) {
            throw new \RuntimeException('No se pudo abrir el archivo de importación.');
        }
        
        //Salteo de primeras lineas
        fgetcsv($handle, 0, ','); // fila 1 (Ficha tecnologica)
        fgetcsv($handle, 0, ','); // fila 2 (Submission details)
        fgetcsv($handle, 0, ','); // fila 3 (encabezados)

        
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            
            // En $row tienes un array con las columnas de la fila actual.
            // Aquí puedes mapearlas a las columnas de tu tabla y guardar en la base de datos.

            //DB::table('solicitantes')->insert([
            $applicant = Applicant::create([
                'IP_address' => $row[6],
                'user' => $row[8],
                'full_name' => $row[60],
                'email' => $row[61],
                'phone' => $row[62],
                'cuil' => $row[63],
                'institution_id' => 1,  //TODO
                'company_name' => $row[65],
                'company_CUIT' => $row[66],
            ]);
            
            DB::table('projects')->insert([
                'SID' => $row[1],
                'title' => $row[9],
                'time_sent' => Carbon::createFromFormat('d/m/Y - H:i', $row[2])->format('Y-m-d H:i:s'),
                'time_completed' => Carbon::createFromFormat('d/m/Y - H:i', $row[3])->format('Y-m-d H:i:s'),
                'time_modified' => Carbon::createFromFormat('d/m/Y - H:i', $row[4])->format('Y-m-d H:i:s'),
                'applicant_id' => $applicant->id,
                'start_date' => Carbon::createFromFormat('d/m/Y', $row[10])->format('Y-m-d'),
                'end_date' => $row[11] == '' ? null : Carbon::createFromFormat('d/m/Y', $row[11])->format('Y-m-d'),
                'objective' => $row[12],
                'innovation_value' => $row[13],
                'trl_level_id' => 1, //TODO
                'area_id' => 1, //TODO
                'located_entity' => $row[67],
                'province_id' => 1, //TODO
                'EBCT_amount' => $row[59]
            ]);
        }

        fclose($handle);

        // Eliminamos el archivo después de procesarlo
        unlink($fullPath);
        return true;
    }

    public static function upload(Request $request): string
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        if (!$request->hasFile('file')) {
            throw new \RuntimeException('Archivo no detectado');
        }

        $file = $request->file('file');

        // Guardamos el archivo manualmente en storage/app/imports
        $directory = storage_path('app/imports');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: 'csv';
        $filename = uniqid('import_', true).'.'.$extension;

        $file->move($directory, $filename);

        // Ruta relativa dentro de storage/app
        $path = 'imports/'.$filename;
        ImporterController::dataImport($path);
        return $path;
        
    }
}
