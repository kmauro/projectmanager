<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = [
            ['institution' => 'Universidad'],
            ['institution' => 'Organismo de gobierno'],
            ['institution' => 'Organismo Científico-Tecnológico (OCT)'],
            ['institution' => 'Mixta (2 o más instituciones del SCT, Gobiernos, Cámaras, etc.)'],
            ['institution' => 'Asociaciones civiles sin fines de lucro (ONGs, Sindicatos, etc.)'],
            ['institution' => 'Empresas, cooperativas, cámaras empresariales'],
            ['institution' => 'Startup/Empresa de Base Científica Tecnológica (EBCT)'],
            ['institution' => 'S.A.P.E.M.'],
        ];

        DB::table('institutions')->insert(
            $institutions
        );
    }
}
