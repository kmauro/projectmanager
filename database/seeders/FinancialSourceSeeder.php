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
            ['name' => 'Agencia I+D+i',
             'type' => 'público'],
             ['name' => 'Subsecretaría de Ciencia y Tecnología de la Nación (ex MINCyT)',
             'type' => 'público'],
             ['name' => 'Ministerio de Desarrollo Productivo de la Nación',
             'type' => 'público'],
             ['name' => 'CFI - Consejo Federal de Inversiones',
             'type' => 'público'],
             ['name' => 'Organismo público provincial/municipal',
             'type' => 'público'],
             ['name' => 'Comisión de Investigaciones Científicas (CIC)',
             'type' => 'público'],
             ['name' => 'Universidades',
             'type' => 'público'],
             ['name' => 'CONICET',
             'type' => 'público'],
             ['name' => 'Secretaría de Políticas Universitarias (SPU)',
             'type' => 'público'],
             ['name' => 'Distinción/Premios',
             'type' => 'público'],
             ['name' => 'Otro',
             'type' => 'público'],
             ['name' => 'No recibió',
             'type' => 'público'],
             ['name' => 'Proveniente del sector bancario',
             'type' => 'privado'],
             ['name' => 'Proveniente de un Fondo de Inversión-venture capital',
             'type' => 'privado'],
             ['name' => 'Proveniente de una Aceleradora',
             'type' => 'privado'],
             ['name' => 'Proveniente de un Inversor Ángel',
             'type' => 'privado'],
             ['name' => 'Proveniente de empresas/cámaras',
             'type' => 'privado'],
             ['name' => 'Proveniente de organismos internacionales',
             'type' => 'privado'],
             ['name' => 'Recursos propios, allegados, familiares o amigos',
             'type' => 'privado'],
             ['name' => 'Distincion/Premios',
             'type' => 'privado'],
             ['name' => 'Otro',
             'type' => 'privado'],
             ['name' => 'No recibió',
             'type' => 'privado'],
        ];

        DB::table('financial_sources')->insert(
            $sources
        );
    }
}
