<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TRLLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['trl' => 'TRL 1 - Principios básicos reportados de la nueva tecnología propuesta.'],
            ['trl' => 'TRL 2 - Formulación del concepto o aplicación de la propuesta tecnológica.'],
            ['trl' => 'TRL 3 - Desarrollo experimental o prueba del concepto tecnológico (Prueba de concepto).'],
            ['trl' => 'TRL 4 - Validación tecnológica en entorno de laboratorio - Pruebas de baja fidelidad.'],
            ['trl' => 'TRL 5 - Validación tecnológica en entorno de laboratorio - Pruebas de alta fidelidad.'],
            ['trl' => 'TRL 6 - Demostración de funcionamiento del prototipo un entorno cercano al real.'],
            ['trl' => 'TRL 7 - Demostración de funcionamiento del prototipo en un entorno relevante.'],
            ['trl' => 'TRL 8 - Tecnología finalizada validada y certificada.'],
            ['trl' => 'TRL 9 - Tecnología en proceso de implementación o comercialización (emprendimiento).']
        ];
        DB::table('trl_levels')->insert(
            $levels
        );

    }
}
