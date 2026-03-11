<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Provincia;;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            ProvinciaSeeder::class,
            VerticalSeeder::class,
            SectorSeeder::class,
            NivelesTRLSeeder::class,
            TecnologiaSeeder::class,
            FuentesFinanciamientoSeeder::class,
            NecesidadSeeder::class,
            InstitucionSeeder::class,
            UserSeeder::class
        ]);
    }
}
