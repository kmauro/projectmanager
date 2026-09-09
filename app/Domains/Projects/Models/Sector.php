<?php

namespace App\Domains\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sector extends Model
{
    public $timestamps = false;

    protected $table = 'sectors';

    protected $fillable = [

    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'projects_sectors');
    }
}
