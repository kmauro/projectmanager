<?php

namespace App\Http\Controllers;

use App\Models\Solicitante;
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
            $solicitante = Solicitante::create([
                'direccion_IP' => $row[6],
                'usuario' => $row[8],
                'nombre_completo' => $row[60],
                'correo' => $row[61],
                'telefono' => $row[62],
                'cuil' => $row[63],
                'institucion_id' => 1,
                'nombre_empresa' => $row[65],
                'empresa_CUIT' => $row[66],
            ]);
            
            DB::table('proyectos')->insert([
                'SID' => $row[1],
                'titulo' => $row[9],
                'hora_enviado' => Carbon::createFromFormat('d/m/Y - H:i', $row[2])->format('Y-m-d H:i:s'),
                'hora_completo' => Carbon::createFromFormat('d/m/Y - H:i', $row[3])->format('Y-m-d H:i:s'),
                'hora_modificado' => Carbon::createFromFormat('d/m/Y - H:i', $row[4])->format('Y-m-d H:i:s'),
                'solicitante_id' => $solicitante->id,
                'fecha_inicio' => Carbon::createFromFormat('d/m/Y', $row[10])->format('Y-m-d'),
                'fecha_fin' => $row[11] == '' ? null : Carbon::createFromFormat('d/m/Y', $row[11])->format('Y-m-d'),
                'objetivo' => $row[12],
                'valor_diferencial' => $row[13],
                'niveles_trl_id' => 1,
                'vertical_id' => 1,
                'entidad_radicado' => $row[67],
                'provincia_id' => 1,
                'monto_EBCT' => $row[59]
                
            ]);

            
            /*
             * Ejemplo (AJUSTAR A TU TABLA REAL):
             *
             * \DB::table('tu_tabla')->insert([
             *     'columna1' => $row[0] ?? null,
             *     'columna2' => $row[1] ?? null,
             *     'columna3' => $row[2] ?? null,
             *     // ...
             *     'created_at' => now(),
             *     'updated_at' => now(),
             * ]);
             */
        }

        fclose($handle);

        // Eliminamos el archivo después de procesarlo
        unlink($fullPath);
        return true;
    }

    public static function upload(Request $request): string
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        if (!$request->hasFile('archivo')) {
            throw new \RuntimeException('Archivo no detectado');
        }

        $file = $request->file('archivo');

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
