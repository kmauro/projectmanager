<?php

namespace App\Domains\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    public $timestamps = false;

    protected $table = 'institutions';

    protected $fillable = [

    ];

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }
}
