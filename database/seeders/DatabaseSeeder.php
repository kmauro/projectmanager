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
            ProvinceSeeder::class,
            AreaSeeder::class,
            SectorSeeder::class,
            TRLLevelSeeder::class,
            TechSeeder::class,
            FinancialSourceSeeder::class,
            NeedSeeder::class,
            InstitutionSeeder::class,
            UserSeeder::class
        ]);
    }
}
