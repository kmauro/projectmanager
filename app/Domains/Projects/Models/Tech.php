<?php

namespace App\Domains\Projects\Models;

use Illuminate\Database\Eloquent\Model;

class Tech extends Model
{
    public $timestamps = false;

    protected $table = 'techs';

    protected $fillable = [

    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projects_techs');
    }
}
