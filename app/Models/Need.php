<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    public $timestamps = false;

    protected $table = 'needs';

    protected $fillable = [
        
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projects_needs');
    }
}
