<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundingSource extends Model
{
    public $timestamps = false;

    protected $table = 'funding_sources';

    protected $fillable = [

    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projects_funding_sources');
    }
}
