<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileImport extends Model
{
    public $timestamps = false;

    protected $table = 'imports';

    protected $fillable = [
        'file_path',
        'status',
        'processed_rows',
        'total_rows',
        'error_message',
        'created_at'
    ];

    
}

