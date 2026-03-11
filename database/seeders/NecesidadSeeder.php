<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NecesidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $necesidades = [
            ['necesidad' => 'Financiamiento',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Personal',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Equipamiento',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Analizadores',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Citometría',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Comatografía',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Equipamientos de ensayos',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Recursos esenciales para el análisis y experimentación, la preparación y conservación de muestras',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Equipamiento/ sistema de medición',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Espectrofotometría',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Espectrometría',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Genómica',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Láseres (como equipo principal)',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Magnetometría',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Microscopía',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Otro equipamiento de alta complejidad',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Radares',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Rayos X',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Resonancia Magnética',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Sortometría',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Conjuntos de instrumentos de investigación científica a gran escala',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Otros (especificar)',
            'necesidad_padre_id' => 3],
            ['necesidad' => 'Insumos',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Capital de trabajo',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Asistencia técnica (legal, administrativa, organizacional, otros)',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Cumplimiento de estándares técnico y sanitario',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Articulación: redes, contactos y networking',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Registro de derecho de propiedad intelectual',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Derecho de autor',
            'necesidad_padre_id' => 28],
            ['necesidad' => 'Marcas',
            'necesidad_padre_id' => 28],
            ['necesidad' => 'Patentes',
            'necesidad_padre_id' => 28],
            ['necesidad' => 'Indicaciones geográficas',
            'necesidad_padre_id' => 28],
            ['necesidad' => 'Variedades vegetales',
            'necesidad_padre_id' => 28],
            ['necesidad' => 'Modelos y diseños industriales',
            'necesidad_padre_id' => 28],
            ['necesidad' => 'Diseño de circuitos integrados de semiconductores',
            'necesidad_padre_id' => 28],
            ['necesidad' => 'Oportunidades en comercio exterior',
            'necesidad_padre_id' => null],
            ['necesidad' => 'Paises',
            'necesidad_padre_id' => 36],
            ['necesidad' => 'Otro (especifique)',
            'necesidad_padre_id' => null],
            
        ];

        DB::table('necesidades')->insert(
            $necesidades
        );
    }
}
