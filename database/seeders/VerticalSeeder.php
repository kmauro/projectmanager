<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class VerticalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $verticales = [
            ['vertical' => 'Agro y biotecnología'],
            ['vertical' => 'Industria'],
            ['vertical' => 'Energía'],
            ['vertical' => 'Salud'],
            ['vertical' => 'Transformación digital'],
            ['vertical' => 'Nanotecnología'],
            ['vertical' => 'Sector espacial y defensa'],
            ['vertical' => 'Sector aeronáutico y marítimo'],
            ['vertical' => 'Educación']
        ];
        DB::table('verticales')->insert(
            $verticales
        );

    }
}
