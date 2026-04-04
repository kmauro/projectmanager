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
        'serial_number' => 'Número de serie',
        'sid' => 'SID',
        'time_sent' => 'Submitted Time',
        'time_completed' => 'Completed Time',
        'time_modified' => 'Modified Time',
        'draft' => 'Borrador',
        'ip_address' => 'Dirección IP',
        'uid' => 'UID',
        'user' => 'Nombre de usuario',
    
        // Proyecto
        'title' => 'Título del proyecto',
        'start_date' => 'Año de inicio del proyecto',
        'end_date' => 'Año de finalización del proyecto',
        'objective' => 'objetivo proyecto',
        'innovation_value' => 'diferencia',
        'trl_level' => 'grado de desarrollo',
        'area' => 'vertical',
        'selected_techs' => 'tecnologías',
        'other_tech' => 'Especificá cuál',
        'application_sector' => 'sector/actividad/área',
    
        // Sectores
        'sector_agriculture_general' => 'Agricultura, ganadería, caza, silvicultura y pesca',
        'sector_agriculture' => 'Agricultura, ganadería, caza y servicios de apoyo',
        'sector_forestry' => 'Silvicultura',
        'sector_mining' => 'Explotación de minas y canteras',
        'sector_oil_gas' => 'petróleo crudo y gas natural',
        'sector_manufacturing' => 'Industria manufacturera',
        'sector_food' => 'productos alimenticios',
        'sector_beverages' => 'bebidas',
        'sector_pharma' => 'farmacéuticos',
        'sector_electronics' => 'informáticos, electrónicos',
        'sector_automotive' => 'vehículos automotores',
        'sector_transport_equipment' => 'equipo de transporte',
        'sector_transport' => 'Transporte y almacenamiento',
        'sector_hospitality' => 'Alojamiento y servicios de comida',
        'sector_it' => 'Información y comunicaciones',
        'sector_finance' => 'Intermediación financiera',
        'sector_health' => 'salud humana',
        'sector_culture' => 'cultura, deporte',
    
        // Financiamiento público
        'public_funding_source' => 'Fuente de financiamiento público',
        'funding_agencia_idi' => 'Agencia I+D+i',
        'funding_mincyt' => 'MINCyT',
        'funding_desarrollo_productivo' => 'Desarrollo Productivo',
        'funding_cfi' => 'CFI',
        'funding_provincial' => 'provincial/municipal',
        'funding_cic' => 'CIC',
        'funding_universities' => 'universidades',
        'funding_conicet' => 'CONICET',
        'funding_spu' => 'SPU',
        'funding_public_awards' => 'premios/distinciones',
        'other_public_funding' => 'Otra institución pública',
    
        // Financiamiento privado
        'private_funding_source' => 'Fuente de financiamiento privado',
        'funding_banks' => 'sector bancario',
        'funding_investment_funds' => 'Fondo de inversión',
        'funding_accelerators' => 'aceleradora',
        'funding_angel' => 'inversor ángel',
        'funding_companies' => 'empresas/cámaras',
        'funding_international' => 'organismos internacionales',
        'funding_own' => 'recursos propios',
        'funding_private_awards' => 'premios/distinciones',
        'other_private_funding' => 'Otro financiamiento privado',
    
        // Startup
        'ebct_amount' => 'EBCT/Startup',
    
        // Persona
        'full_name' => 'Nombre y apellido',
        'email' => 'Correo electrónico',
        'phone' => 'Teléfono de contacto',
        'cuil' => 'CUIL',
    
        // Institución
        'institution' => 'tipo de institución',
        'company_name' => 'Nombre de la empresa',
        'company_cuit' => 'CUIT',
        'located_entity' => 'entidad o institución',
        'province' => 'provincia',
    
        // Escalamiento
        'needs' => 'Necesidades para el escalamiento',
        'equipment' => 'Equipamiento',
        'other_equipment' => 'Especificá cuál',
        'ip_rights' => 'propiedad intelectual',
        'countries' => 'máximo tres países',
        'other_needs' => 'Otras necesidades',
    ];
    


    /**
     * Transforms the CSV file rows into an array
     */
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
        fgetcsv($handle, 0, ',', '"', '\\'); // row 1
        fgetcsv($handle, 0, ',', '"', '\\'); // row 2

        // Read header row (row 3)
        $headers = fgetcsv($handle, 0, ',', '"', '\\');
        if (!$headers) {
            throw new \RuntimeException('No se pudo leer la fila de encabezados.');
        }

        $this->mapColumnHeaders($headers);
        $projectRegistry = [];

        while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            
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
                    'countries' => $this->getValue($row, 73, 'countries'),
                    'needs' => [
                        'selectedNeeds' => $this->getValue($row, 69, 'needs'),
                        'selectedEquipment' => $this->getValue($row, 70, 'equipment'),
                        'selectedRights' => $this->getValue($row, 72, 'ip_rights')
                    ],
                    'other_needs' => [
                        'other_equipment' => $this->getValue($row, 71, 'other_equipment'),
                        'other_need' => $this->getValue($row, 74, 'other_needs'),
                    ],
                    ''
                ]
            ]);
        }

        fclose($handle);
        unlink($path);

        return collect($projectRegistry);
    }

    /**
     * Compares the HEADER_MAPPING constant to the headers of the file to know where are the
     * columns and upload the information correctly.
     */
    function mapColumnHeaders(array $fileHeaders) {
        
    
        // normalizar headers una sola vez
        $normalizedHeaders = [];
        foreach ($fileHeaders as $i => $header) {
            $normalizedHeaders[$i] = $this->normalize($header);
        }
        $mapping = self::HEADER_MAPPING;
        //Buscamos similitudes entre los titulos y las constantes
        foreach ($mapping as $key => $searchText) {
            $normalizedSearch = $this->normalize($searchText);

            foreach ($normalizedHeaders as $index => $header) {
                if (str_contains($header, $normalizedSearch)) {
                    $this->columnMap[$key] = $index;
                    unset($normalizedHeaders[$index]);
                    break;
                }
            }            
        }
    
    }

    
    /**
     * Finds the value using the mapped columns, if the mapped column is not found
     * it uses the default index where it should be.
     */
    private function getValue(array $row, int $defaultIndex, string $mapKey): string
    {
        // Use the map if available, otherwise fallback to default index (legacy support)
        // Note: In a production environment, we should probably only rely on the map.
        
        
        $index = (!empty($this->columnMap[$mapKey]))
            ? $this->columnMap[$mapKey] 
            : $defaultIndex;
        
        $value = $row[$index] ?? '';
        
        return $this->normalize($value);
    }

    /**
     * Reformat the datetime to the format that MySQL uses so we can use a DATETIME
     * field instead of a string field
     */
    private function parseDateTime(?string $value): ?string
    {
        if (empty($value)) return null;
        try {
            return Carbon::createFromFormat('d/m/Y - H:i', $value)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Reformat the date to the format that MySQL uses so we can use a DATE
     * field instead of a string field
     */
    private function parseDate(?string $value): ?string
    {
        if (empty($value)) return null;
        try {
            return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Eliminates tabs and duplicated spaces so the data collected is normalized and
     * not in a weird format.
     */
    public function normalize(string $data): string
    {
        $data = trim($data);
        $data = str_replace("\t", " ", $data);
        return preg_replace('/\s+/', ' ', $data);
    }
}
