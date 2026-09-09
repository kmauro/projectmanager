<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['province' => 'Buenos Aires'],
            ['province' => 'Ciudad Autónoma de Buenos Aires'],
            ['province' => 'Catamarca'],
            ['province' => 'Chaco'],
            ['province' => 'Chubut'],
            ['province' => 'Córdoba'],
            ['province' => 'Corrientes'],
            ['province' => 'Entre Ríos'],
            ['province' => 'Formosa'],
            ['province' => 'Jujuy'],
            ['province' => 'La Pampa'],
            ['province' => 'La Rioja'],
            ['province' => 'Mendoza'],
            ['province' => 'Misiones'],
            ['province' => 'Neuquén'],
            ['province' => 'Río Negro'],
            ['province' => 'Salta'],
            ['province' => 'San Juan'],
            ['province' => 'San Luis'],
            ['province' => 'Santa Cruz'],
            ['province' => 'Santa Fe'],
            ['province' => 'Santiago del Estero'],
            ['province' => 'Tierra del Fuego - Antártida e Islas del Atlántico Sur'],
            ['province' => 'Tucumán']
        ];
        DB::table('provinces')->insert(
            $provinces
        );
    }
}
