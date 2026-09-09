<?php

use App\Domains\Files\Services\ProjectImportParser;
use App\Domains\Files\Services\ProjectPersistenceService;
use App\Domains\Files\Services\SectorMatcher;
use App\Domains\Projects\Models\Area;
use App\Domains\Projects\Models\Institution;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Models\Province;
use App\Domains\Projects\Models\TRLLevel;
use App\Domains\Projects\Repositories\SectorRepository;
use Database\Seeders\SectorSeeder;
use Illuminate\Support\Facades\Storage;

it('normalizes sector values and resolves duplicate names using their CSV hierarchy', function () {
    $this->seed(SectorSeeder::class);

    $sectorIds = app(SectorMatcher::class)->matchIds([
        'application_sector' => '  EXPLOTACIÓN DE MINAS Y CANTERAS, Industria manufacturera, Servicios profesionales, científicos y técnicos ',
        'sector_agriculture_general' => 'Pesca, acuicultura y servicios de apoyo',
        'sector_mining' => 'Explotación de minas y canteras',
        'sector_beverages' => 'Elaboración de bebidas',
        'sector_electronics' => 'Fabricación de partes, piezas y accesorios, para vehículos automotores y sus motores',
    ], app(SectorRepository::class)->getSectorsForMatching());

    expect($sectorIds)->toEqualCanonicalizing([10, 11, 17, 19, 28, 51, 79]);
});

it('rejects sector values that do not exist in the seeded catalog', function () {
    $this->seed(SectorSeeder::class);

    expect(fn () => app(SectorMatcher::class)->matchIds([
        'application_sector' => 'Sector inventado',
    ], app(SectorRepository::class)->getSectorsForMatching()))
        ->toThrow(RuntimeException::class, "El sector 'Sector inventado' no existe en la base de datos.");
});

it('extracts all sector columns from the imported CSV', function () {
    $headers = array_map(fn (int $index): string => "Columna {$index}", range(0, 74));
    $headers[18] = '¿En qué sector/actividad/área se puede aplicar la tecnología desarrollada en el marco del proyecto?';
    $headers[19] = 'Agricultura, ganadería, caza, silvicultura y pesca';
    $headers[20] = 'Agricultura, ganadería, caza y servicios de apoyo';
    $headers[21] = 'Silvicultura, extracción de productos forestales y servicios de apoyo';
    $headers[22] = 'Explotación de minas y canteras';
    $headers[23] = 'Extracción de petróleo crudo y gas natural';
    $headers[24] = 'Industria manufacturera';
    $headers[25] = 'Elaboración de productos alimenticios';
    $headers[26] = 'Elaboración de bebidas';
    $headers[27] = 'Fabricación de productos farmacéuticos';
    $headers[28] = 'Fabricación de productos informáticos, electrónicos y ópticos';
    $headers[29] = 'Fabricación de vehículos automotores, remolques y semirremolques';
    $headers[30] = 'Fabricación de equipo de transporte';
    $headers[31] = 'Transporte y almacenamiento';
    $headers[32] = 'Alojamiento y servicios de comida';
    $headers[33] = 'Información y comunicaciones';
    $headers[34] = 'Intermediación financiera y servicios de seguros';
    $headers[35] = 'Atención a la salud humana';
    $headers[36] = 'Artístico, cultura, deporte y esparcimiento';

    $row = array_fill(0, 75, '');
    $row[18] = 'Salud humana y servicios sociales';
    $row[22] = 'Extracción de minerales metalíferos';
    $row[35] = 'Servicios de hospitales';

    $stream = fopen('php://temp', 'w+');
    fputcsv($stream, array_fill(0, 75, ''), ',', '"', '\\');
    fputcsv($stream, range(0, 74), ',', '"', '\\');
    fputcsv($stream, $headers, ',', '"', '\\');
    fputcsv($stream, $row, ',', '"', '\\');
    rewind($stream);

    $filePath = 'imports/sector-parser-test.csv';
    Storage::disk('local')->put($filePath, stream_get_contents($stream));
    fclose($stream);

    $sectors = app(ProjectImportParser::class)
        ->parseFile($filePath)
        ->first()['project']['sectors'];

    expect($sectors)
        ->toHaveCount(19)
        ->and($sectors['application_sector'])->toBe('Salud humana y servicios sociales')
        ->and($sectors['sector_mining'])->toBe('Extracción de minerales metalíferos')
        ->and($sectors['sector_health'])->toBe('Servicios de hospitales');
});

it('persists the matched sector ids in the project pivot table', function () {
    $this->seed(SectorSeeder::class);

    Institution::query()->insert(['institution' => 'Universidad']);
    Area::query()->create(['area' => 'Industria']);
    Province::query()->create(['province' => 'Buenos Aires']);
    TRLLevel::query()->create(['trl_level' => 'TRL 3']);

    app(ProjectPersistenceService::class)->persistToDatabase(collect([
        collect([
            'applicant' => [
                'IP_address' => '127.0.0.1',
                'user' => 'usuario-prueba',
                'full_name' => 'Persona de prueba',
                'email' => 'persona@example.com',
                'phone' => '',
                'cuil' => '',
                'institution_id' => 'Universidad',
                'company_name' => '',
                'company_CUIT' => '',
            ],
            'project' => [
                'SID' => 987654,
                'title' => 'Proyecto sectorial',
                'time_sent' => null,
                'time_completed' => null,
                'time_modified' => null,
                'start_date' => null,
                'end_date' => null,
                'objective' => '',
                'innovation_value' => '',
                'trl_level_id' => 'TRL 3',
                'area_id' => 'Industria',
                'located_entity' => '',
                'province_id' => 'Buenos Aires',
                'EBCT_amount' => '',
                'selectedTechs' => '',
                'other_tech' => '',
                'countries' => '',
                'needs' => [
                    'selectedNeeds' => '',
                    'selectedEquipment' => '',
                    'selectedRights' => '',
                ],
                'other_needs' => [
                    'other_equipment' => '',
                    'other_need' => '',
                ],
                'sectors' => [
                    'application_sector' => 'Salud humana y servicios sociales',
                    'sector_health' => 'Servicios de hospitales',
                ],
                'funding_sources' => [
                    'public' => ['selected' => '', 'percentages' => [], 'other' => ''],
                    'private' => ['selected' => '', 'percentages' => [], 'other' => ''],
                ],
            ],
        ]),
    ]));

    $project = Project::query()->where('SID', 987654)->firstOrFail();

    expect($project->sectors()->orderBy('sectors.id')->pluck('sectors.id')->all())
        ->toBe([83, 85]);
});
