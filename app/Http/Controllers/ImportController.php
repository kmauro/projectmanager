<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Jobs\DataImportService;
use App\Http\Controllers\ProjectController;
use App\Models\Import;
use App\Models\Tech;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
        foreach($dbData as $record){
            if(str_contains($fileData, $record->name)){
                $ids[] = $record->id;
            }
        }
        return $ids;
    }

    public static function existsOtherTech($selectedTechs, $otherTechs)
    {
        return str_contains($selectedTechs, 'Otro') && !empty($otherTechs);
        
    }

    public static function searchPredefinedTechs($selectedTechs, $predefinedTechs) :array
    {
        $ids = [];
        foreach($predefinedTechs as $predefinedTech){
            
            if(str_contains($selectedTechs, $predefinedTech->name)){
                $ids[] = $predefinedTech->id;
            }
        }
        return $ids;
    }

    public static function saveSelectedTechs($selectedTechs, $otherTechs, $predefinedTechs) :array
    {
        $ids = ImportController::searchPredefinedTechs($selectedTechs, $predefinedTechs);

        if(ImportController::existsOtherTech($selectedTechs, $otherTechs)){            
            $otherID = DB::table('techs')->insertGetId([
                'name' => $otherTechs,
                'predefined' => false
            ]);
            $ids[] = $otherID;
        }
        return $ids;
    }
}
