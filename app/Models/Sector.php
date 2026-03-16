<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    public $timestamps = false;

    protected $table = 'sectors';

    protected $fillable = [
        
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projects_sectors');
    }
}
