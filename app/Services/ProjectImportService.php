<?php

namespace App\Services;

use App\Repositories\FileImportRepository;

class ProjectImportService
{
    public function __construct(
        private FileImportRepository $importRepository,
        private ProjectImportParser $parser
    ) {}

    public function processUpload($file)
    {
        $uploadedFilePath = $this->saveFile($file);

        $this->importRepository->createImportRecord($uploadedFilePath);

        $projectRegistry = $this->parser->parseFile($uploadedFilePath);

        $this->parser->persistToDatabase($projectRegistry);
    }

    private function saveFile($file)
    {
        $directory = storage_path('app/imports');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: 'csv';
        $filename = uniqid('import_', true).'.'.$extension;

        $file->move($directory, $filename);

        return 'imports/'.$filename;
    }
}
