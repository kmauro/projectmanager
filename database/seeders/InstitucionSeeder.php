<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instituciones = [
            ['institucion' => 'Universidad'],
            ['institucion' => 'Organismo de gobierno'],
            ['institucion' => 'Organismo Científico-Tecnológico (OCT)'],
            ['institucion' => 'Mixta (2 o más instituciones del SCT, Gobiernos, Cámaras, etc.)'],
            ['institucion' => 'Asociaciones civiles sin fines de lucro (ONGs, Sindicatos, etc.)'],
            ['institucion' => 'Empresas, cooperativas, cámaras empresariales'],
            ['institucion' => 'Startup / Empresa de Base Científica Tecnológica (EBCT)'],
            ['institucion' => 'S.A.P.E.M.'],
        ];

        DB::table('instituciones')->insert(
            $instituciones
        );
    }
}
