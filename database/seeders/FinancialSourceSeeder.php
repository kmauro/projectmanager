<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            ['source' => 'Agencia I+D+i',
             'type' => 'público'],
             ['source' => 'Subsecretaría de Ciencia y Tecnología de la Nación (ex MINCyT)',
             'type' => 'público'],
             ['source' => 'Ministerio de Desarrollo Productivo de la Nación',
             'type' => 'público'],
             ['source' => 'CFI - Consejo Federal de Inversiones',
             'type' => 'público'],
             ['source' => 'Organismo público provincial/municipal',
             'type' => 'público'],
             ['source' => 'Comisión de Investigaciones Científicas (CIC)',
             'type' => 'público'],
             ['source' => 'Universidades',
             'type' => 'público'],
             ['source' => 'CONICET',
             'type' => 'público'],
             ['source' => 'Secretaría de Políticas Universitarias (SPU)',
             'type' => 'público'],
             ['source' => 'Distinción/Premios',
             'type' => 'público'],
             ['source' => 'Otro',
             'type' => 'público'],
             ['source' => 'No recibió',
             'type' => 'público'],
             ['source' => 'Proveniente del sector bancario',
             'type' => 'privado'],
             ['source' => 'Proveniente de un Fondo de Inversión-venture capital',
             'type' => 'privado'],
             ['source' => 'Proveniente de una Aceleradora',
             'type' => 'privado'],
             ['source' => 'Proveniente de un Inversor Ángel',
             'type' => 'privado'],
             ['source' => 'Proveniente de empresas/cámaras',
             'type' => 'privado'],
             ['source' => 'Proveniente de organismos internacionales',
             'type' => 'privado'],
             ['source' => 'Recursos propios, allegados, familiares o amigos',
             'type' => 'privado'],
             ['source' => 'Distincion/Premios',
             'type' => 'privado'],
             ['source' => 'Otro',
             'type' => 'privado'],
             ['source' => 'No recibió',
             'type' => 'privado'],
        ];

        DB::table('financial_sources')->insert(
            $sources
        );
    }
}
