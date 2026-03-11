<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $needs = [
            ['need' => 'Financiamiento',
            'parent_need_id' => null],
            ['need' => 'Personal',
            'parent_need_id' => null],
            ['need' => 'Equipamiento',
            'parent_need_id' => null],
            ['need' => 'Analizadores',
            'parent_need_id' => 3],
            ['need' => 'Citometría',
            'parent_need_id' => 3],
            ['need' => 'Comatografía',
            'parent_need_id' => 3],
            ['need' => 'Equipamientos de ensayos',
            'parent_need_id' => 3],
            ['need' => 'Recursos esenciales para el análisis y experimentación, la preparación y conservación de muestras',
            'parent_need_id' => 3],
            ['need' => 'Equipamiento/ sistema de medición',
            'parent_need_id' => 3],
            ['need' => 'Espectrofotometría',
            'parent_need_id' => 3],
            ['need' => 'Espectrometría',
            'parent_need_id' => 3],
            ['need' => 'Genómica',
            'parent_need_id' => 3],
            ['need' => 'Láseres (como equipo principal)',
            'parent_need_id' => 3],
            ['need' => 'Magnetometría',
            'parent_need_id' => 3],
            ['need' => 'Microscopía',
            'parent_need_id' => 3],
            ['need' => 'Otro equipamiento de alta complejidad',
            'parent_need_id' => 3],
            ['need' => 'Radares',
            'parent_need_id' => 3],
            ['need' => 'Rayos X',
            'parent_need_id' => 3],
            ['need' => 'Resonancia Magnética',
            'parent_need_id' => 3],
            ['need' => 'Sortometría',
            'parent_need_id' => 3],
            ['need' => 'Conjuntos de instrumentos de investigación científica a gran escala',
            'parent_need_id' => 3],
            ['need' => 'Otros (especificar)',
            'parent_need_id' => 3],
            ['need' => 'Insumos',
            'parent_need_id' => null],
            ['need' => 'Capital de trabajo',
            'parent_need_id' => null],
            ['need' => 'Asistencia técnica (legal, administrativa, organizacional, otros)',
            'parent_need_id' => null],
            ['need' => 'Cumplimiento de estándares técnico y sanitario',
            'parent_need_id' => null],
            ['need' => 'Articulación: redes, contactos y networking',
            'parent_need_id' => null],
            ['need' => 'Registro de derecho de propiedad intelectual',
            'parent_need_id' => null],
            ['need' => 'Derecho de autor',
            'parent_need_id' => 28],
            ['need' => 'Marcas',
            'parent_need_id' => 28],
            ['need' => 'Patentes',
            'parent_need_id' => 28],
            ['need' => 'Indicaciones geográficas',
            'parent_need_id' => 28],
            ['need' => 'Variedades vegetales',
            'parent_need_id' => 28],
            ['need' => 'Modelos y diseños industriales',
            'parent_need_id' => 28],
            ['need' => 'Diseño de circuitos integrados de semiconductores',
            'parent_need_id' => 28],
            ['need' => 'Oportunidades en comercio exterior',
            'parent_need_id' => null],
            ['need' => 'Paises',
            'parent_need_id' => 36],
            ['need' => 'Otro (especifique)',
            'parent_need_id' => null],
            
        ];

        DB::table('needs')->insert(
            $needs
        );
    }
}
