<?php

namespace App\Repositories;

use App\Models\FileImport;

class FileImportRepository
{
    public function createImportRecord($record){
        FileImport::create([
            'file_path' => $record,
            'status' => 'pending',
        ]);
    }
}