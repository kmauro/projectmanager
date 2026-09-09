<?php

namespace App\Domains\Files\Services;

use App\Domains\Projects\Models\Sector;
use Illuminate\Support\Str;
use RuntimeException;

class SectorMatcher
{
    private const COLUMN_PARENT_NAMES = [
        'application_sector' => null,
        'sector_agriculture_general' => 'Agricultura, ganadería, caza, silvicultura y pesca',
        'sector_agriculture' => 'Agricultura, ganadería, caza y servicios de apoyo',
        'sector_forestry' => 'Silvicultura, extracción de productos forestales y servicios de apoyo',
        'sector_mining' => 'Explotación de minas y canteras',
        'sector_oil_gas' => 'Extracción de petróleo crudo y gas natural',
        'sector_manufacturing' => 'Industria manufacturera',
        'sector_food' => 'Elaboración de productos alimenticios',
        'sector_beverages' => 'Elaboración de bebidas',
        'sector_pharma' => 'Fabricación de productos farmacéuticos',
        'sector_electronics' => 'Fabricación de productos informáticos, electrónicos y ópticos',
        'sector_automotive' => 'Fabricación de vehículos automotores, remolques y semirremolques',
        'sector_transport_equipment' => 'Fabricación de equipo de transporte',
        'sector_transport' => 'Transporte y almacenamiento',
        'sector_hospitality' => 'Alojamiento y servicios de comida',
        'sector_it' => 'Información y comunicaciones',
        'sector_finance' => 'Intermediación financiera y servicios de seguros',
        'sector_health' => 'Atención a la salud humana',
        'sector_culture' => 'Artístico, cultura, deporte y esparcimiento',
    ];

    /**
     * @param  array<string, string>  $selectedSectorValues
     * @param  iterable<int, Sector>  $sectors
     * @return array<int, int>
     */
    public function matchIds(array $selectedSectorValues, iterable $sectors): array
    {
        [$sectorsByNormalizedName, $parentSectorIds] = $this->buildSectorIndex($sectors);
        $expectedParentIds = $this->resolveExpectedParentIds($sectorsByNormalizedName, $parentSectorIds);
        $sectorIds = [];

        foreach ($selectedSectorValues as $columnKey => $selectedValue) {
            if (trim($selectedValue) === '') {
                continue;
            }

            if (! array_key_exists($columnKey, $expectedParentIds)) {
                throw new RuntimeException("La columna de sector '{$columnKey}' no está configurada.");
            }

            $sectorIds = [
                ...$sectorIds,
                ...$this->matchValue(
                    $selectedValue,
                    $expectedParentIds[$columnKey],
                    $sectorsByNormalizedName,
                ),
            ];
        }

        return array_values(array_unique($sectorIds));
    }

    /**
     * @param  iterable<int, Sector>  $sectors
     * @return array{0: array<string, array<int, Sector>>, 1: array<int, true>}
     */
    private function buildSectorIndex(iterable $sectors): array
    {
        $sectorsByNormalizedName = [];
        $parentSectorIds = [];

        foreach ($sectors as $sector) {
            $sectorsByNormalizedName[$this->normalize($sector->name)][] = $sector;

            if ($sector->parent_sector_id !== null) {
                $parentSectorIds[(int) $sector->parent_sector_id] = true;
            }
        }

        return [$sectorsByNormalizedName, $parentSectorIds];
    }

    /**
     * @param  array<string, array<int, Sector>>  $sectorsByNormalizedName
     * @param  array<int, true>  $parentSectorIds
     * @return array<string, int|null>
     */
    private function resolveExpectedParentIds(array $sectorsByNormalizedName, array $parentSectorIds): array
    {
        $expectedParentIds = [];

        foreach (self::COLUMN_PARENT_NAMES as $columnKey => $parentName) {
            $expectedParentIds[$columnKey] = $parentName === null
                ? null
                : $this->resolveParentId($parentName, $sectorsByNormalizedName, $parentSectorIds);
        }

        return $expectedParentIds;
    }

    /**
     * @param  array<string, array<int, Sector>>  $sectorsByNormalizedName
     * @param  array<int, true>  $parentSectorIds
     */
    private function resolveParentId(
        string $parentName,
        array $sectorsByNormalizedName,
        array $parentSectorIds,
    ): int {
        $normalizedParentName = $this->normalize($parentName);
        $matchingParentIds = [];

        foreach ($sectorsByNormalizedName as $normalizedSectorName => $sectors) {
            if (! str_contains($normalizedSectorName, $normalizedParentName)
                && ! str_contains($normalizedParentName, $normalizedSectorName)) {
                continue;
            }

            foreach ($sectors as $sector) {
                if (isset($parentSectorIds[(int) $sector->id])) {
                    $matchingParentIds[] = (int) $sector->id;
                }
            }
        }

        $matchingParentIds = array_values(array_unique($matchingParentIds));

        if (count($matchingParentIds) !== 1) {
            throw new RuntimeException("No se pudo identificar el sector padre '{$parentName}'.");
        }

        return $matchingParentIds[0];
    }

    /**
     * @param  array<string, array<int, Sector>>  $sectorsByNormalizedName
     * @return array<int, int>
     */
    private function matchValue(
        string $selectedValue,
        ?int $expectedParentId,
        array $sectorsByNormalizedName,
    ): array {
        $normalizedValue = $this->normalize($selectedValue);
        $matchingNames = $this->findMostSpecificMatchingNames(
            $normalizedValue,
            array_keys($sectorsByNormalizedName),
        );

        if ($matchingNames === []) {
            throw new RuntimeException("El sector '{$selectedValue}' no existe en la base de datos.");
        }

        $remainingValue = $normalizedValue;
        $sectorIds = [];

        foreach ($matchingNames as $matchingName) {
            $remainingValue = str_replace($matchingName, ' ', $remainingValue);
            $candidates = $sectorsByNormalizedName[$matchingName];
            $contextCandidates = array_values(array_filter(
                $candidates,
                fn (Sector $sector): bool => $this->parentId($sector) === $expectedParentId,
            ));

            if (count($contextCandidates) === 1) {
                $sectorIds[] = (int) $contextCandidates[0]->id;

                continue;
            }

            if (count($candidates) === 1) {
                $sectorIds[] = (int) $candidates[0]->id;

                continue;
            }

            throw new RuntimeException("El sector '{$selectedValue}' es ambiguo en la base de datos.");
        }

        if ($this->normalize($remainingValue) !== '') {
            throw new RuntimeException("El sector '{$selectedValue}' contiene valores que no existen en la base de datos.");
        }

        return $sectorIds;
    }

    /**
     * @param  array<int, string>  $normalizedSectorNames
     * @return array<int, string>
     */
    private function findMostSpecificMatchingNames(string $normalizedValue, array $normalizedSectorNames): array
    {
        $matchingNames = array_values(array_filter(
            $normalizedSectorNames,
            fn (string $sectorName): bool => str_contains($normalizedValue, $sectorName),
        ));

        usort($matchingNames, fn (string $first, string $second): int => strlen($second) <=> strlen($first));

        return array_values(array_filter(
            $matchingNames,
            fn (string $sectorName): bool => ! collect($matchingNames)->contains(
                fn (string $otherName): bool => strlen($otherName) > strlen($sectorName)
                    && str_contains($otherName, $sectorName),
            ),
        ));
    }

    private function parentId(Sector $sector): ?int
    {
        return $sector->parent_sector_id === null ? null : (int) $sector->parent_sector_id;
    }

    private function normalize(string $value): string
    {
        return Str::of($value)
            ->transliterate()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->replaceMatches('/\b(?:y|e)\b/', ' ')
            ->squish()
            ->toString();
    }
}
