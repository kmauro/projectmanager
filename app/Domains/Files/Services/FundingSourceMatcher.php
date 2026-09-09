<?php

namespace App\Domains\Files\Services;

use App\Domains\Projects\Models\FundingSource;
use App\Domains\Projects\Repositories\FundingSourceRepository;
use Illuminate\Support\Str;
use RuntimeException;

class FundingSourceMatcher
{
    private const TYPE_NAMES = [
        'public' => 'público',
        'private' => 'privado',
    ];

    private const SOURCE_DEFINITIONS = [
        'public' => [
            ['selection' => 'Agencia I+D+i', 'source' => 'Agencia I+D+i', 'percentage_key' => 'funding_agencia_idi'],
            ['selection' => 'Subsecretaría de Ciencia y Tecnología de la Nación (ex MINCyT)', 'source' => 'Subsecretaría de Ciencia y Tecnología de la Nación (ex MINCyT)', 'percentage_key' => 'funding_mincyt'],
            ['selection' => 'Ministerio de Desarrollo Productivo de la Nación', 'source' => 'Ministerio de Desarrollo Productivo de la Nación', 'percentage_key' => 'funding_desarrollo_productivo'],
            ['selection' => 'CFI - Consejo Federal de Inversiones', 'source' => 'CFI - Consejo Federal de Inversiones', 'percentage_key' => 'funding_cfi'],
            ['selection' => 'Organismo público provincial/municipal', 'source' => 'Organismo público provincial/municipal', 'percentage_key' => 'funding_provincial'],
            ['selection' => 'Comisión de Investigaciones Científicas (CIC)', 'source' => 'Comisión de Investigaciones Científicas (CIC)', 'percentage_key' => 'funding_cic'],
            ['selection' => 'Universidades', 'source' => 'Universidades', 'percentage_key' => 'funding_universities'],
            ['selection' => 'CONICET', 'source' => 'CONICET', 'percentage_key' => 'funding_conicet'],
            ['selection' => 'Secretaría de Políticas Universitarias (SPU)', 'source' => 'Secretaría de Políticas Universitarias (SPU)', 'percentage_key' => 'funding_spu'],
            ['selection' => 'Distinción/Premios', 'source' => 'Distinción/Premios', 'percentage_key' => 'funding_public_awards'],
            ['selection' => 'No recibió financiamiento', 'source' => 'No recibió', 'percentage_key' => null],
        ],
        'private' => [
            ['selection' => 'Proveniente del sector bancario', 'source' => 'Proveniente del sector bancario', 'percentage_key' => 'funding_banks'],
            ['selection' => 'Proveniente de un Fondo de Inversión-venture capital', 'source' => 'Proveniente de un Fondo de Inversión-venture capital', 'percentage_key' => 'funding_investment_funds'],
            ['selection' => 'Proveniente de una Aceleradora', 'source' => 'Proveniente de una Aceleradora', 'percentage_key' => 'funding_accelerators'],
            ['selection' => 'Proveniente de un Inversor Ángel', 'source' => 'Proveniente de un Inversor Ángel', 'percentage_key' => 'funding_angel'],
            ['selection' => 'Proveniente de empresas/cámaras', 'source' => 'Proveniente de empresas/cámaras', 'percentage_key' => 'funding_companies'],
            ['selection' => 'Proveniente de organismos internacionales', 'source' => 'Proveniente de organismos internacionales', 'percentage_key' => 'funding_international'],
            ['selection' => 'Recursos propios, allegados, familiares o amigos.', 'source' => 'Recursos propios, allegados, familiares o amigos', 'percentage_key' => 'funding_own'],
            ['selection' => 'Distinción/premios', 'source' => 'Distincion/Premios', 'percentage_key' => 'funding_private_awards'],
            ['selection' => 'No recibió financiamiento', 'source' => 'No recibió', 'percentage_key' => null],
        ],
    ];

    private const CUSTOM_SOURCE_DEFINITIONS = [
        'public' => [
            'selection' => 'Otra institución pública',
            'generic_name' => 'Otra institución pública',
        ],
        'private' => [
            'selection' => 'Otro',
            'generic_name' => 'Otro financiamiento privado',
        ],
    ];

    /** @var array<string, int> */
    private array $customSourceIds = [];

    public function __construct(private FundingSourceRepository $fundingSourceRepository) {}

    /**
     * @param  array{
     *     public: array{selected: string, percentages: array<string, string>, other: string},
     *     private: array{selected: string, percentages: array<string, string>, other: string}
     * }  $fundingValues
     * @param  iterable<int, FundingSource>  $fundingSources
     * @return array<int, array{source_id: int, percentage: float}>
     */
    public function match(array $fundingValues, iterable $fundingSources): array
    {
        $sourceIds = $this->buildSourceIndex($fundingSources);
        $matchedSources = [];

        foreach (array_keys(self::TYPE_NAMES) as $typeKey) {
            $matchedSources = [
                ...$matchedSources,
                ...$this->matchType($typeKey, $fundingValues[$typeKey], $sourceIds),
            ];
        }

        return array_values(array_reduce(
            $matchedSources,
            function (array $sources, array $source): array {
                $sources[$source['source_id']] = $source;

                return $sources;
            },
            [],
        ));
    }

    /**
     * @param  iterable<int, FundingSource>  $fundingSources
     * @return array<string, array<string, int>>
     */
    private function buildSourceIndex(iterable $fundingSources): array
    {
        $sourceIds = ['public' => [], 'private' => []];

        foreach ($fundingSources as $fundingSource) {
            $typeKey = $this->typeKey($fundingSource->type);
            $normalizedName = $this->normalize($fundingSource->name);

            if ($fundingSource->predefined) {
                if (isset($sourceIds[$typeKey][$normalizedName])) {
                    throw new RuntimeException("La fuente '{$fundingSource->name}' está duplicada en el catálogo.");
                }

                $sourceIds[$typeKey][$normalizedName] = (int) $fundingSource->id;

                continue;
            }

            $this->customSourceIds[$this->customSourceKey($typeKey, $fundingSource->name)] = (int) $fundingSource->id;
        }

        return $sourceIds;
    }

    /**
     * @param  array{selected: string, percentages: array<string, string>, other: string}  $fundingValues
     * @param  array<string, array<string, int>>  $sourceIds
     * @return array<int, array{source_id: int, percentage: float}>
     */
    private function matchType(string $typeKey, array $fundingValues, array $sourceIds): array
    {
        $remainingSelection = $this->normalize($fundingValues['selected']);

        if ($remainingSelection === '') {
            return [];
        }

        $matchedSources = [];

        foreach (self::SOURCE_DEFINITIONS[$typeKey] as $definition) {
            $normalizedSelection = $this->normalize($definition['selection']);

            if (! str_contains($remainingSelection, $normalizedSelection)) {
                continue;
            }

            $remainingSelection = str_replace($normalizedSelection, ' ', $remainingSelection);
            $sourceId = $sourceIds[$typeKey][$this->normalize($definition['source'])] ?? null;

            if ($sourceId === null) {
                throw new RuntimeException("La fuente '{$definition['source']}' no existe en la base de datos.");
            }

            $percentageValue = $definition['percentage_key'] === null
                ? ''
                : ($fundingValues['percentages'][$definition['percentage_key']] ?? '');

            $matchedSources[] = [
                'source_id' => $sourceId,
                'percentage' => $this->normalizePercentage($percentageValue),
            ];
        }

        $customDefinition = self::CUSTOM_SOURCE_DEFINITIONS[$typeKey];
        $normalizedCustomSelection = $this->normalize($customDefinition['selection']);

        if (str_contains($remainingSelection, $normalizedCustomSelection)) {
            $remainingSelection = str_replace($normalizedCustomSelection, ' ', $remainingSelection);
            $matchedSources[] = $this->matchCustomSource(
                $typeKey,
                $fundingValues['other'],
                $customDefinition['generic_name'],
            );
        }

        if ($this->normalize($remainingSelection) !== '') {
            throw new RuntimeException("La fuente '{$fundingValues['selected']}' no existe en la base de datos.");
        }

        return $matchedSources;
    }

    /**
     * @return array{source_id: int, percentage: float}
     */
    private function matchCustomSource(string $typeKey, string $otherValue, string $genericName): array
    {
        $percentage = $this->numericPercentageOrNull($otherValue);
        $sourceName = $percentage === null && trim($otherValue) !== ''
            ? trim($otherValue)
            : $genericName;

        if (Str::length($sourceName) > 255) {
            throw new RuntimeException("La fuente personalizada '{$sourceName}' supera los 255 caracteres.");
        }

        $customSourceKey = $this->customSourceKey($typeKey, $sourceName);
        $sourceId = $this->customSourceIds[$customSourceKey]
            ??= $this->fundingSourceRepository->firstOrCreateCustom(
                $sourceName,
                self::TYPE_NAMES[$typeKey],
            );

        return [
            'source_id' => $sourceId,
            'percentage' => $percentage ?? 0.0,
        ];
    }

    private function normalizePercentage(string $value): float
    {
        if (trim($value) === '') {
            return 0.0;
        }

        $percentage = $this->numericPercentageOrNull($value);

        if ($percentage === null) {
            throw new RuntimeException("El porcentaje '{$value}' no es numérico.");
        }

        return $percentage;
    }

    private function numericPercentageOrNull(string $value): ?float
    {
        $normalizedValue = Str::of($value)
            ->replace(['%', ' '], '')
            ->replace(',', '.')
            ->toString();

        if ($normalizedValue === '' || ! is_numeric($normalizedValue)) {
            return null;
        }

        $percentage = (float) $normalizedValue;

        if ($percentage < 0) {
            throw new RuntimeException("El porcentaje '{$value}' no puede ser negativo.");
        }

        return $percentage;
    }

    private function typeKey(string $type): string
    {
        return match ($this->normalize($type)) {
            $this->normalize(self::TYPE_NAMES['public']) => 'public',
            $this->normalize(self::TYPE_NAMES['private']) => 'private',
            default => throw new RuntimeException("El tipo de financiamiento '{$type}' no está configurado."),
        };
    }

    private function customSourceKey(string $typeKey, string $sourceName): string
    {
        return $typeKey.'|'.$this->normalize($sourceName);
    }

    private function normalize(string $value): string
    {
        return Str::of($value)
            ->transliterate()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->squish()
            ->toString();
    }
}
