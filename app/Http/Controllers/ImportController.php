<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Jobs\DataImportService;
use App\Http\Controllers\ProjectController;
use App\Models\Import;
use App\Models\Tech;


class ImportController extends Controller
{
    

    public static function upload(Request $request)
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

        $import = Import::create([
            'file_path' => $path,
            'status' => 'pending',
        ]);
        
        
        DataImportService::dispatch($import);
        
    
        return back()->with('success', 'Archivo subido correctamente');
        
        return ProjectController::index();
        
    }




    public static function dataNormalizer($fileData)
    {
        $normalizedFileData = $fileData;
        $normalizedFileData = trim($normalizedFileData);
        $normalizedFileData = str_replace("\t", " ", $normalizedFileData);
        $normalizedFileData = preg_replace('/\s+/', ' ', $normalizedFileData);

        return $normalizedFileData;
    }


    public static function dataCheck($fileData, $dbData) :array
    {
        $ids = [];
        $index = 0;
        foreach($dbData as $record){
            if(str_contains($fileData, $record->name)){
                $ids[$index] = $record->id;
                $index++;
            }
        }
        return $ids;
    }
}
