<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitanteFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'direccion_IP',
        'usuario',
        'nombre_completo',
        'correo',
        'telefono',
        'cuil',
        'institucion_id',
        'nombre_empresa',
        'empresa_CUIT'
    ];
}
