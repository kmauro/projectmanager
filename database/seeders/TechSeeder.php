<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techs = [
            ['name' => 'Biotecnología'],
            ['name' => 'Nanotecnología y materiales avanzados'],
            ['name' => 'TICs software/hardware y tecnologías de transformación digital (Big Data, Inteligencia Artificial (IA), Internet de las Cosas (IoT), Software / SaaS, Computación en la Nube, Robótica y electrónica, Impresión 3D, Enterprise/IT, Software/Blockchain)'],
            ['name' => 'Otro'],
        ];

        DB::table('techs')->insert(
            $techs
        );
    }
}
