<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;

    protected $table = 'provinces';

    protected $fillable = [
        
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
