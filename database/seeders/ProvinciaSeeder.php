<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provincias = [
            ['provincia' => 'Buenos Aires'],
            ['provincia' => 'Ciudad Autónoma de Buenos Aires'],
            ['provincia' => 'Catamarca'],
            ['provincia' => 'Chaco'],
            ['provincia' => 'Chubut'],
            ['provincia' => 'Córdoba'],
            ['provincia' => 'Corrientes'],
            ['provincia' => 'Entre Rios'],
            ['provincia' => 'Formosa'],
            ['provincia' => 'Jujuy'],
            ['provincia' => 'La Pampa'],
            ['provincia' => 'La Rioja'],
            ['provincia' => 'Mendoza'],
            ['provincia' => 'Misiones'],
            ['provincia' => 'Neuquén'],
            ['provincia' => 'Río Negro'],
            ['provincia' => 'Salta'],
            ['provincia' => 'San Juan'],
            ['provincia' => 'San Luís'],
            ['provincia' => 'Santa Cruz'],
            ['provincia' => 'Santa Fe'],
            ['provincia' => 'Santiago del Estero'],
            ['provincia' => 'Tierra del Fuego - Antártida e Islas del Atlántico Sur'],
            ['provincia' => 'Tucumán']
        ];
        DB::table('provincias')->insert(
            $provincias
        );
    }
}
