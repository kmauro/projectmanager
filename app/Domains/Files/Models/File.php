<?php

namespace App\Domains\Files\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
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

