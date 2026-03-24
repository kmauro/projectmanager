<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProjectImportService;

class ProjectImportController extends Controller
{
    public function __construct(
        private ProjectImportService $importService
    ) {}

    public function showUploadForm()
    {
        return view('files.upload');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        try {
            $this->importService->processUpload($request->file('file'));

            return back()->with('success', 'Archivo subido correctamente, ingresando datos...');
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'Error al importar: '.$e->getMessage()]);
        }
    }
}
