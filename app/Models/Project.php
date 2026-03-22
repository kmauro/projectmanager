<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    //Parametros de BD
    public $timestamps = false;

    protected $table = 'projects';

    protected $fillable = [
        
    ];


    //Métodos



    //Relaciones de objetos
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function trllevel()
    {
         return $this->belongsTo(TRLLevel::class);
    }

    public function techs()
    {
        return $this->belongsToMany(Tech::class, 'projects_techs');
    }

    public function needs()
    {
        return $this->belongsToMany(Need::class, 'projects_needs');
    }

    public function financialsources()
    {
        return $this->belongsToMany(FinancialSource::class, 'projects_financial_sources');
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class, 'projects_sectors');
    }
}

