<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;



use App\Services\UploadProjectRegistryService;
use Illuminate\Support\Facades\DB;



class FileImportController extends Controller
{
    
    public static function getUploadFileView()
    {
        return view('files.upload');
    }

    public static function uploadFile(Request $request)
    {
        //Realizamos la validacion que el usuario haya
        //cargado correctamente el archivo
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        if (!$request->hasFile('file')) {
            throw new \RuntimeException('Archivo no detectado');
        }

        $file = $request->file('file');
        
        $uploadFile = app(UploadProjectRegistryService::class);
        $uploadFile->uploadProjectRegistryFile($file);
    }




    
}
