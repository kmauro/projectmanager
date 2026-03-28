<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ProjectImportParser
{
    private array $columnMap = [];

    /**
     * Define the mapping between internal keys and CSV header names.
     */
    private const HEADER_MAPPING = [
        'ip_address' => 'IP Address',
        'user' => 'User',
        'full_name' => 'Nombre completo',
        'email' => 'Email',
        'phone' => 'Teléfono',
        'cuil' => 'CUIL',
        'institution' => 'Institución',
        'company_name' => 'Nombre empresa',
        'company_cuit' => 'CUIT empresa',
        'sid' => 'SID',
        'title' => 'Título',
        'time_sent' => 'Fecha envío',
        'time_completed' => 'Fecha completado',
        'time_modified' => 'Fecha modificado',
        'start_date' => 'Fecha inicio',
        'end_date' => 'Fecha fin',
        'objective' => 'Objetivo',
        'innovation_value' => 'Valor innovación',
        'trl_level' => 'Nivel TRL',
        'area' => 'Área',
        'located_entity' => 'Entidad',
        'province' => 'Provincia',
        'ebct_amount' => 'Monto EBCT',
        'selected_techs' => 'Tecnologías',
        'other_tech' => 'Otras tecnologías',
    ];

    public function parseFile(string $uploadedFilePath): Collection
    {
        $path = storage_path('app/'.$uploadedFilePath);

        if (!file_exists($path)) {
            throw new \RuntimeException('El archivo de importación no existe: '.$path);
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \RuntimeException('No se pudo abrir el archivo de importación.');
        }

        // Skip initial descriptive rows
        fgetcsv($handle); // row 1
        fgetcsv($handle); // row 2
        
        // Read header row (row 3)
        $headers = fgetcsv($handle);
        if (!$headers) {
            throw new \RuntimeException('No se pudo leer la fila de encabezados.');
        }

        $this->buildColumnMap($headers);

        $projectRegistry = [];

        while (($row = fgetcsv($handle)) !== false) {
            $projectRegistry[] = collect([
                'applicant' => [
                    'IP_address' => $this->getValue($row, 6, 'ip_address'),
                    'user' => $this->getValue($row, 8, 'user'),
                    'full_name' => $this->getValue($row, 60, 'full_name'),
                    'email' => $this->getValue($row, 61, 'email'),
                    'phone' => $this->getValue($row, 62, 'phone'),
                    'cuil' => $this->getValue($row, 63, 'cuil'),
                    'institution_id' => $this->getValue($row, 64, 'institution'),
                    'company_name' => $this->getValue($row, 65, 'company_name'),
                    'company_CUIT' => $this->getValue($row, 66, 'company_cuit'),
                ],
                'project' => [
                    'SID' => $this->getValue($row, 1, 'sid'),
                    'title' => $this->getValue($row, 9, 'title'),
                    'time_sent' => $this->parseDateTime($this->getValue($row, 2, 'time_sent')),
                    'time_completed' => $this->parseDateTime($this->getValue($row, 3, 'time_completed')),
                    'time_modified' => $this->parseDateTime($this->getValue($row, 4, 'time_modified')),
                    'start_date' => $this->parseDate($this->getValue($row, 10, 'start_date')),
                    'end_date' => $this->parseDate($this->getValue($row, 11, 'end_date')),
                    'objective' => $this->getValue($row, 12, 'objective'),
                    'innovation_value' => $this->getValue($row, 13, 'innovation_value'),
                    'trl_level_id' => $this->getValue($row, 14, 'trl_level'),
                    'area_id' => $this->getValue($row, 15, 'area'),
                    'located_entity' => $this->getValue($row, 67, 'located_entity'),
                    'province_id' => $this->getValue($row, 68, 'province'),
                    'EBCT_amount' => $this->getValue($row, 59, 'ebct_amount'),
                    'selectedTechs' => $this->getValue($row, 16, 'selected_techs'),
                    'other_tech' => $this->getValue($row, 17, 'other_tech'),
                ],
            ]);
        }

        fclose($handle);
        unlink($path);

        return collect($projectRegistry);
    }

    private function buildColumnMap(array $headers): void
    {
        $this->columnMap = array_flip(array_map('trim', $headers));
    }

    private function getValue(array $row, int $defaultIndex, string $mapKey): string
    {
        // Use the map if available, otherwise fallback to default index (legacy support)
        // Note: In a production environment, we should probably only rely on the map.
        $headerName = self::HEADER_MAPPING[$mapKey] ?? null;
        $index = ($headerName && isset($this->columnMap[$headerName])) 
            ? $this->columnMap[$headerName] 
            : $defaultIndex;

        $value = $row[$index] ?? '';
        return DataNormalizer::normalize($value);
    }

    private function parseDateTime(?string $value): ?string
    {
        if (empty($value)) return null;
        try {
            return Carbon::createFromFormat('d/m/Y - H:i', $value)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }

    private function parseDate(?string $value): ?string
    {
        if (empty($value)) return null;
        try {
            return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
