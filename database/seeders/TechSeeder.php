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
            ['name' => 'Biotecnología', 
            'predefined' => true ],
            ['name' => 'Nanotecnología y materiales avanzados', 
            'predefined' => true ],
            ['name' => 'TICs software/hardware y tecnologías de transformación digital (Big Data, Inteligencia Artificial (IA), Internet de las Cosas (IoT), Software / SaaS, Computación en la Nube, Robótica y electrónica, Impresión 3D, Enterprise/IT, Software/Blockchain)', 
            'predefined' => true ],
            
        ];

        DB::table('techs')->insert(
            $techs
        );
    }
}
