<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialSource extends Model
{
    public $timestamps = false;

    protected $table = 'financial_sources';

    protected $fillable = [

    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projects_financial_sources');
    }
}
