<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TecnologiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tecnologias = [
            ['tecnologia' => 'Biotecnología'],
            ['tecnologia' => 'Nanotecnología y materiales avanzados'],
            ['tecnologia' => 'TICs software/hardware y tecnologías de transformación digital (Big Data, Inteligencia Artificial (IA), Internet de las Cosas (IoT), Software / SaaS, Computación en la Nube, Robótica y electrónica, Impresión 3D, Enterprise/IT, Software/Blockchain)'],
            ['tecnologia' => 'Otro'],
        ];

        DB::table('tecnologias')->insert(
            $tecnologias
        );
    }
}
