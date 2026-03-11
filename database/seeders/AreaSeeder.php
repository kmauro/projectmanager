<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['area' => 'Agro y biotecnología'],
            ['area' => 'Industria'],
            ['area' => 'Energía'],
            ['area' => 'Salud'],
            ['area' => 'Transformación digital'],
            ['area' => 'Nanotecnología'],
            ['area' => 'Sector espacial y defensa'],
            ['area' => 'Sector aeronáutico y marítimo'],
            ['area' => 'Educación']
        ];
        DB::table('areas')->insert(
            $areas
        );

    }
}
