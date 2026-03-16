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
            ['name' => 'Financiamiento',
            'parent_need_id' => null],
            ['name' => 'Personal',
            'parent_need_id' => null],
            ['name' => 'Equipamiento',
            'parent_need_id' => null],
            ['name' => 'Analizadores',
            'parent_need_id' => 3],
            ['name' => 'Citometría',
            'parent_need_id' => 3],
            ['name' => 'Comatografía',
            'parent_need_id' => 3],
            ['name' => 'Equipamientos de ensayos',
            'parent_need_id' => 3],
            ['name' => 'Recursos esenciales para el análisis y experimentación, la preparación y conservación de muestras',
            'parent_need_id' => 3],
            ['name' => 'Equipamiento/ sistema de medición',
            'parent_need_id' => 3],
            ['name' => 'Espectrofotometría',
            'parent_need_id' => 3],
            ['name' => 'Espectrometría',
            'parent_need_id' => 3],
            ['name' => 'Genómica',
            'parent_need_id' => 3],
            ['name' => 'Láseres (como equipo principal)',
            'parent_need_id' => 3],
            ['name' => 'Magnetometría',
            'parent_need_id' => 3],
            ['name' => 'Microscopía',
            'parent_need_id' => 3],
            ['name' => 'Otro equipamiento de alta complejidad',
            'parent_need_id' => 3],
            ['name' => 'Radares',
            'parent_need_id' => 3],
            ['name' => 'Rayos X',
            'parent_need_id' => 3],
            ['name' => 'Resonancia Magnética',
            'parent_need_id' => 3],
            ['name' => 'Sortometría',
            'parent_need_id' => 3],
            ['name' => 'Conjuntos de instrumentos de investigación científica a gran escala',
            'parent_need_id' => 3],
            ['name' => 'Otros (especificar)',
            'parent_need_id' => 3],
            ['name' => 'Insumos',
            'parent_need_id' => null],
            ['name' => 'Capital de trabajo',
            'parent_need_id' => null],
            ['name' => 'Asistencia técnica (legal, administrativa, organizacional, otros)',
            'parent_need_id' => null],
            ['name' => 'Cumplimiento de estándares técnico y sanitario',
            'parent_need_id' => null],
            ['name' => 'Articulación: redes, contactos y networking',
            'parent_need_id' => null],
            ['name' => 'Registro de derecho de propiedad intelectual',
            'parent_need_id' => null],
            ['name' => 'Derecho de autor',
            'parent_need_id' => 28],
            ['name' => 'Marcas',
            'parent_need_id' => 28],
            ['name' => 'Patentes',
            'parent_need_id' => 28],
            ['name' => 'Indicaciones geográficas',
            'parent_need_id' => 28],
            ['name' => 'Variedades vegetales',
            'parent_need_id' => 28],
            ['name' => 'Modelos y diseños industriales',
            'parent_need_id' => 28],
            ['name' => 'Diseño de circuitos integrados de semiconductores',
            'parent_need_id' => 28],
            ['name' => 'Oportunidades en comercio exterior',
            'parent_need_id' => null],
            ['name' => 'Paises',
            'parent_need_id' => 36],
            ['name' => 'Otro (especifique)',
            'parent_need_id' => null],
            
        ];

        DB::table('needs')->insert(
            $needs
        );
    }
}
