<?php

use App\Domains\Files\Services\FundingSourceMatcher;
use App\Domains\Files\Services\ProjectImportParser;
use App\Domains\Files\Services\ProjectPersistenceService;
use App\Domains\Projects\Models\Area;
use App\Domains\Projects\Models\FundingSource;
use App\Domains\Projects\Models\Institution;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Models\Province;
use App\Domains\Projects\Models\TRLLevel;
use App\Domains\Projects\Repositories\FundingSourceRepository;
use Database\Seeders\FundingSourceSeeder;
use Database\Seeders\SectorSeeder;
use Illuminate\Support\Facades\Storage;

it('matches public and private funding sources with their percentages', function () {
    $this->seed(FundingSourceSeeder::class);

    $matches = app(FundingSourceMatcher::class)->match([
        'public' => [
            'selected' => 'Agencia I+D+i,Organismo público provincial/municipal,Ministerio de Desarrollo Productivo de la Nación',
            'percentages' => [
                'funding_agencia_idi' => '50',
                'funding_desarrollo_productivo' => '20',
                'funding_provincial' => '30',
            ],
            'other' => '',
        ],
        'private' => [
            'selected' => 'Proveniente del sector bancario,Proveniente de organismos internacionales,Proveniente de un Inversor Ángel',
            'percentages' => [
                'funding_banks' => '50',
                'funding_angel' => '20%',
                'funding_international' => '30',
            ],
            'other' => '',
        ],
    ], app(FundingSourceRepository::class)->getFundingSourcesForMatching());

    $matchesBySourceId = collect($matches)->keyBy('source_id');
    $sourceIds = FundingSource::query()->pluck('id', 'name');

    expect($matchesBySourceId)
        ->toHaveCount(6)
        ->and($matchesBySourceId[$sourceIds['Agencia I+D+i']]['percentage'])->toBe(50.0)
        ->and($matchesBySourceId[$sourceIds['Ministerio de Desarrollo Productivo de la Nación']]['percentage'])->toBe(20.0)
        ->and($matchesBySourceId[$sourceIds['Organismo público provincial/municipal']]['percentage'])->toBe(30.0)
        ->and($matchesBySourceId[$sourceIds['Proveniente del sector bancario']]['percentage'])->toBe(50.0)
        ->and($matchesBySourceId[$sourceIds['Proveniente de un Inversor Ángel']]['percentage'])->toBe(20.0)
        ->and($matchesBySourceId[$sourceIds['Proveniente de organismos internacionales']]['percentage'])->toBe(30.0);
});

it('creates custom funding sources from text or numeric other values', function () {
    $this->seed(FundingSourceSeeder::class);

    $matches = app(FundingSourceMatcher::class)->match([
        'public' => [
            'selected' => 'Otra institución pública',
            'percentages' => [],
            'other' => 'Instituto Nacional de Tecnología Industrial',
        ],
        'private' => [
            'selected' => 'Otro',
            'percentages' => [],
            'other' => '25',
        ],
    ], app(FundingSourceRepository::class)->getFundingSourcesForMatching());

    $publicSource = FundingSource::query()
        ->where('name', 'Instituto Nacional de Tecnología Industrial')
        ->where('type', 'público')
        ->firstOrFail();
    $privateSource = FundingSource::query()
        ->where('name', 'Otro financiamiento privado')
        ->where('type', 'privado')
        ->firstOrFail();
    $matchesBySourceId = collect($matches)->keyBy('source_id');

    expect($publicSource->predefined)->toBeFalse()
        ->and($matchesBySourceId[$publicSource->id]['percentage'])->toBe(0.0)
        ->and($privateSource->predefined)->toBeFalse()
        ->and($matchesBySourceId[$privateSource->id]['percentage'])->toBe(25.0);
});

it('rejects non numeric values in percentage columns', function () {
    $this->seed(FundingSourceSeeder::class);

    expect(fn () => app(FundingSourceMatcher::class)->match([
        'public' => [
            'selected' => 'Agencia I+D+i',
            'percentages' => ['funding_agencia_idi' => 'sin porcentaje'],
            'other' => '',
        ],
        'private' => ['selected' => '', 'percentages' => [], 'other' => ''],
    ], app(FundingSourceRepository::class)->getFundingSourcesForMatching()))
        ->toThrow(RuntimeException::class, "El porcentaje 'sin porcentaje' no es numérico.");
});

it('preserves numeric values above one hundred and decimal commas from the source file', function () {
    $this->seed(FundingSourceSeeder::class);

    $matches = app(FundingSourceMatcher::class)->match([
        'public' => [
            'selected' => 'Agencia I+D+i',
            'percentages' => ['funding_agencia_idi' => '30000,5'],
            'other' => '',
        ],
        'private' => ['selected' => '', 'percentages' => [], 'other' => ''],
    ], app(FundingSourceRepository::class)->getFundingSourcesForMatching());

    expect($matches[0]['percentage'])->toBe(30000.5);
});

it('extracts funding names, percentages and custom values from the imported CSV', function () {
    $headers = array_map(fn (int $index): string => "Columna {$index}", range(0, 74));
    $headers[37] = 'Fuente de financiamiento público';
    $headers[38] = 'Porcentaje de financiamiento de la Agencia I+D+i';
    $headers[39] = 'Porcentaje de financiamiento de la Subsecretaría de Ciencia y Tecnología de la Nación (ex MINCyT)';
    $headers[40] = 'Porcentaje de financiamiento del Ministerio de Desarrollo Productivo de la Nación';
    $headers[41] = 'Porcentaje de financiamiento Consejo Federal de Inversiones (CFI)';
    $headers[42] = 'Porcentaje de financiamiento de organismo público provincial/municipal';
    $headers[43] = 'Porcentaje de financiamiento de la Comisión de Investigaciones Científicas (CIC)';
    $headers[44] = 'Porcentaje de financiamiento de universidades';
    $headers[45] = 'Porcentaje de financiamiento del CONICET';
    $headers[46] = 'Porcentaje de financiamiento de la Secretaría de Políticas Universitarias (SPU)';
    $headers[47] = 'Porcentaje de financiamiento premios/distinciones';
    $headers[48] = 'Otra institución pública';
    $headers[49] = 'Fuente de financiamiento privado';
    $headers[50] = 'Porcentaje de financiamiento sector bancario';
    $headers[51] = 'Porcentaje de financiamiento Fondo de inversión';
    $headers[52] = 'Porcentaje de financiamiento aceleradora';
    $headers[53] = 'Porcentaje de financiamiento inversor ángel';
    $headers[54] = 'Porcentaje de financiamiento empresas/cámaras';
    $headers[55] = 'Porcentaje de financiamiento organismos internacionales';
    $headers[56] = 'Porcentaje de financiamiento recursos propios';
    $headers[57] = 'Porcentaje de financiamiento premios/distinciones';
    $headers[58] = 'Otro financiamiento privado';

    $row = array_fill(0, 75, '');
    $row[37] = 'Agencia I+D+i,Universidades';
    $row[38] = '55.5';
    $row[44] = '44,5';
    $row[49] = 'Recursos propios, allegados, familiares o amigos.,Otro';
    $row[56] = '76%';
    $row[58] = 'Capitales privados';

    $stream = fopen('php://temp', 'w+');
    fputcsv($stream, array_fill(0, 75, ''), ',', '"', '\\');
    fputcsv($stream, range(0, 74), ',', '"', '\\');
    fputcsv($stream, $headers, ',', '"', '\\');
    fputcsv($stream, $row, ',', '"', '\\');
    rewind($stream);

    $filePath = 'imports/funding-source-parser-test.csv';
    Storage::disk('local')->put($filePath, stream_get_contents($stream));
    fclose($stream);

    $fundingSources = app(ProjectImportParser::class)
        ->parseFile($filePath)
        ->first()['project']['funding_sources'];

    expect($fundingSources['public']['selected'])->toBe('Agencia I+D+i,Universidades')
        ->and($fundingSources['public']['percentages']['funding_agencia_idi'])->toBe('55.5')
        ->and($fundingSources['public']['percentages']['funding_universities'])->toBe('44,5')
        ->and($fundingSources['private']['selected'])->toBe('Recursos propios, allegados, familiares o amigos.,Otro')
        ->and($fundingSources['private']['percentages']['funding_own'])->toBe('76%')
        ->and($fundingSources['private']['other'])->toBe('Capitales privados');
});

it('persists funding source ids and percentages in the project pivot table', function () {
    $this->seed([SectorSeeder::class, FundingSourceSeeder::class]);

    Institution::query()->insert(['institution' => 'Universidad']);
    Area::query()->create(['area' => 'Industria']);
    Province::query()->create(['province' => 'Buenos Aires']);
    TRLLevel::query()->create(['trl_level' => 'TRL 3']);

    app(ProjectPersistenceService::class)->persistToDatabase(collect([
        collect([
            'applicant' => [
                'IP_address' => '127.0.0.1',
                'user' => 'usuario-financiamiento',
                'full_name' => 'Persona de prueba',
                'email' => 'financiamiento@example.com',
                'phone' => '',
                'cuil' => '',
                'institution_id' => 'Universidad',
                'company_name' => '',
                'company_CUIT' => '',
            ],
            'project' => [
                'SID' => 987655,
                'title' => 'Proyecto financiado',
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
                'sectors' => [],
                'funding_sources' => [
                    'public' => [
                        'selected' => 'Agencia I+D+i,Universidades',
                        'percentages' => [
                            'funding_agencia_idi' => '60',
                            'funding_universities' => '40',
                        ],
                        'other' => '',
                    ],
                    'private' => [
                        'selected' => 'No recibió financiamiento',
                        'percentages' => [],
                        'other' => '',
                    ],
                ],
            ],
        ]),
    ]));

    $fundingSources = Project::query()
        ->where('SID', 987655)
        ->firstOrFail()
        ->fundingSources()
        ->get();

    $agency = $fundingSources->firstWhere('name', 'Agencia I+D+i');
    $universities = $fundingSources->firstWhere('name', 'Universidades');
    $noPrivateFunding = $fundingSources->first(
        fn (FundingSource $source): bool => $source->name === 'No recibió' && $source->type === 'privado',
    );

    expect($fundingSources)->toHaveCount(3)
        ->and((float) $agency->pivot->percentage)->toBe(60.0)
        ->and((float) $universities->pivot->percentage)->toBe(40.0)
        ->and((float) $noPrivateFunding->pivot->percentage)->toBe(0.0);
});
