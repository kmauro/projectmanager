<?php

namespace App\Domains\Files\Controllers;

use App\Domains\Files\Requests\ImportFileRequest;
use App\Domains\Files\Services\ProjectImportService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Throwable;

class FileController extends Controller
{
    public function __construct(
        private ProjectImportService $importService
    ) {}

    public function showCreate(): InertiaResponse
    {
        return Inertia::render('Projects/addProjectsFile', [
            'title' => 'Agregar Proyectos',
        ]);
    }

    public function store(ImportFileRequest $request): RedirectResponse
    {
        $validated = $request->validatedNewData();

        try {
            $this->importService->processUpload($validated['file']);

            return back()->with('success', 'Archivo subido correctamente, ingresando datos...');
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'No se pudo importar el archivo. Revisa su formato e inténtalo nuevamente.');
        }
    }
}
