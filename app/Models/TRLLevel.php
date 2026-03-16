<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TRLLevel extends Model
{
    protected $table = 'trl_levels';
    public $timestamps = false;

    protected $fillable = [
        
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
