<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * @deprecated Use ProcessProjectTechRegistryJob instead
 */
class UploadTechRegistryJob implements ShouldQueue
{
    use Queueable;

    protected string $uploadedFilePath;

    public function __construct(string $uploadedFilePath)
    {
        $this->uploadedFilePath = $uploadedFilePath;
    }

    public function handle(): void
    {
        $parser = app(\App\Services\ProjectTechRegistryParser::class);
        $projectRegistry = $parser->parseFile($this->uploadedFilePath);
        $parser->persistToDatabase($projectRegistry);
    }
}
