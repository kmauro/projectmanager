<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FuentesFinanciamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fuentes = [
            ['fuente' => 'Agencia I+D+i',
             'tipo' => 'público'],
             ['fuente' => 'Subsecretaría de Ciencia y Tecnología de la Nación (ex MINCyT)',
             'tipo' => 'público'],
             ['fuente' => 'Ministerio de Desarrollo Productivo de la Nación',
             'tipo' => 'público'],
             ['fuente' => 'CFI - Consejo Federal de Inversiones',
             'tipo' => 'público'],
             ['fuente' => 'Organismo público provincial/municipal',
             'tipo' => 'público'],
             ['fuente' => 'Comisión de Investigaciones Científicas (CIC)',
             'tipo' => 'público'],
             ['fuente' => 'Universidades',
             'tipo' => 'público'],
             ['fuente' => 'CONICET',
             'tipo' => 'público'],
             ['fuente' => 'Secretaría de Políticas Universitarias (SPU)',
             'tipo' => 'público'],
             ['fuente' => 'Distinción/Premios',
             'tipo' => 'público'],
             ['fuente' => 'Otro',
             'tipo' => 'público'],
             ['fuente' => 'No recibió',
             'tipo' => 'público'],
             ['fuente' => 'Proveniente del sector bancario',
             'tipo' => 'privado'],
             ['fuente' => 'Proveniente de un Fondo de Inversión-venture capital',
             'tipo' => 'privado'],
             ['fuente' => 'Proveniente de una Aceleradora',
             'tipo' => 'privado'],
             ['fuente' => 'Proveniente de un Inversor Ángel',
             'tipo' => 'privado'],
             ['fuente' => 'Proveniente de empresas/cámaras',
             'tipo' => 'privado'],
             ['fuente' => 'Proveniente de organismos internacionales',
             'tipo' => 'privado'],
             ['fuente' => 'Recursos propios, allegados, familiares o amigos',
             'tipo' => 'privado'],
             ['fuente' => 'Distincion/Premios',
             'tipo' => 'privado'],
             ['fuente' => 'Otro',
             'tipo' => 'privado'],
             ['fuente' => 'No recibió',
             'tipo' => 'privado'],
        ];

        DB::table('fuentes_financiamiento')->insert(
            $fuentes
        );
    }
}
