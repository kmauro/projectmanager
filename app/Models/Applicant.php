<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitanteFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'applicants';

    protected $fillable = [
        
    ];


    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function institution()
    {
        return $this->hasMany(Institution::class);
    }
}
