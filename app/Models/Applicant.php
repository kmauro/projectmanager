<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitanteFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'IP_address',
        'user',
        'full_name',
        'email',
        'phone',
        'cuil',
        'institution_id',
        'company_name',
        'company_CUIT'
    ];
}
