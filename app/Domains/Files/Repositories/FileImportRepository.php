<?php

namespace App\Domains\Files\Repositories;

use App\Domains\Files\Models\File;

class FileImportRepository
{
    public function createImportRecord($record){
        return File::insert([
            'file_path' => $record,
            'status' => 'pending',
        ]);
    }
}
