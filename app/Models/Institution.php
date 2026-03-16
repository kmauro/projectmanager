<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    public $timestamps = false;

    protected $table = 'institutions';

    protected $fillable = [
        
    ];

    public function applicants()
    {
        return $this->belongsTo(Applicant::class);

    }
}
