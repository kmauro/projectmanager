<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public $timestamps = false;

    protected $table = 'areas';

    protected $fillable = [
        
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
