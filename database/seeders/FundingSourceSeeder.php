<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FundingSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            ['name' => 'Agencia I+D+i',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'Subsecretaría de Ciencia y Tecnología de la Nación (ex MINCyT)',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'Ministerio de Desarrollo Productivo de la Nación',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'CFI - Consejo Federal de Inversiones',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'Organismo público provincial/municipal',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'Comisión de Investigaciones Científicas (CIC)',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'Universidades',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'CONICET',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'Secretaría de Políticas Universitarias (SPU)',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'Distinción/Premios',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'No recibió',
             'type' => 'público',
             'predefined' => true ],
             ['name' => 'Proveniente del sector bancario',
             'type' => 'privado',
             'predefined' => true ],
             ['name' => 'Proveniente de un Fondo de Inversión-venture capital',
             'type' => 'privado',
             'predefined' => true ],
             ['name' => 'Proveniente de una Aceleradora',
             'type' => 'privado',
             'predefined' => true ],
             ['name' => 'Proveniente de un Inversor Ángel',
             'type' => 'privado',
             'predefined' => true ],
             ['name' => 'Proveniente de empresas/cámaras',
             'type' => 'privado',
             'predefined' => true ],
             ['name' => 'Proveniente de organismos internacionales',
             'type' => 'privado',
             'predefined' => true ],
             ['name' => 'Recursos propios, allegados, familiares o amigos',
             'type' => 'privado',
             'predefined' => true ],
             ['name' => 'Distincion/Premios',
             'type' => 'privado',
             'predefined' => true ],
             ['name' => 'No recibió',
             'type' => 'privado',
             'predefined' => true ],
        ];

        DB::table('funding_sources')->insert(
            $sources
        );
    }
}
