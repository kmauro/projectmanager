<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\UploadProjectRegistryService;

class UploadTechRegistryJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

     protected $uploadedFilePath;
     protected $uploadService;

    public function __construct($uploadedFilePath)
    {
        $this->uploadedFilePath = $uploadedFilePath;
        $this->uploadService = app(UploadProjectRegistryService::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //transformamos los registros en una coleccion
        $projectRegistry = $this->uploadService->convertRegistryToCollection($this->uploadedFilePath);

        //guardamos la coleccion en la base de datos
        $this->uploadService->uploadProjectRegistry($projectRegistry);

        //MODAL OK
    }
}
