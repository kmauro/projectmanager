<?php

namespace App\Domains\Files\Services;

use App\Domains\Files\Repositories\FileImportRepository;

class ProjectImportService
{
    public function __construct(
        private FileImportRepository $importRepository,
        private ProjectImportParser $parser,
        private ProjectPersistenceService $persistenceService
    ) {}

    public function processUpload($file)
    {
        $uploadedFilePath = $this->saveFile($file);

        $this->importRepository->createImportRecord($uploadedFilePath);

        $projectRegistry = $this->parser->parseFile($uploadedFilePath);

        $this->persistenceService->persistToDatabase($projectRegistry);
    }

    private function saveFile($file)
    {
        $filePath = $file->store('imports', 'local');

        if (! is_string($filePath) || $filePath === '') {
            throw new \RuntimeException('No se pudo guardar temporalmente el archivo importado.');
        }

        return $filePath;
    }
}
