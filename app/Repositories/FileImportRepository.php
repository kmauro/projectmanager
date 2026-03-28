<?php

namespace App\Repositories;

use App\Models\FileImport;

class FileImportRepository
{
    public function createImportRecord($record){
        return FileImport::insert([
            'file_path' => $record,
            'status' => 'pending',
        ]);
    }
}