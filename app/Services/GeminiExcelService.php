<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class GeminiExcelService
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        
        if (empty($this->apiKey)) {
            throw new \Exception('GEMINI_API_KEY no está configurada en el archivo .env');
        }
    }

    public function procesarExcel($archivoExcel)
    {
        Log::info('=== INICIO GEMINI ===');
        
        // Leer el Excel
        $data = Excel::toArray([], $archivoExcel);
        $rows = isset($data[1]) ? $data[1] : $data[0];
        
        Log::info('Filas leídas: ' . count($rows));
        
        // Limitar filas
        $rows = array_slice($rows, 0, 100);
        
        // Convertir a CSV
        $csv = $this->arrayToCsv($rows);
        
        Log::info('CSV generado, longitud: ' . strlen($csv));
        
        // Enviar a Gemini
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Extrae los datos de este Excel y devuelve SOLO un JSON válido.

                            El JSON debe tener este formato exacto:
                            {
                                \"items\": [
                                    {
                                        \"clave\": \"codigo del producto\",
                                        \"descripcion\": \"descripcion del producto\",
                                        \"unidad\": \"unidad de medida\",
                                        \"cantidad\": 0,
                                        \"link\": \"url opcional\",
                                        \"observaciones\": \"texto opcional\"
                                    }
                                ]
                            }

                            REGLAS IMPORTANTES:
                            1. Las columnas del Excel son: Clave, Descripción, Unidad, Cantidad
                            2. Si una fila tiene 'N/A' como clave, usa la descripción para identificar el producto
                            3. Ignora filas vacías o que sean encabezados
                            4. Ignora filas donde la clave sea un número (1, 2, 3...)
                            5. Devuelve SOLO el JSON, sin texto adicional

                            Datos del Excel:
                            $csv"
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1,
                'maxOutputTokens' => 8192,
            ]
        ]);

        Log::info('Respuesta de Gemini - Status: ' . $response->status());

        if (!$response->successful()) {
            Log::error('Error en Gemini: ' . $response->body());
            throw new \Exception('Error en la API de Gemini: ' . $response->body());
        }

        $data = $response->json();
        Log::info('Respuesta JSON: ' . json_encode($data));
        
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        Log::info('Texto extraído: ' . $text);
        
        // Extraer JSON del texto
        preg_match('/\{.*\}/s', $text, $matches);
        $jsonText = $matches[0] ?? $text;
        Log::info('JSON extraído: ' . $jsonText);
        
        $resultado = json_decode($jsonText, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error al decodificar JSON: ' . json_last_error_msg());
            throw new \Exception('Error al decodificar JSON: ' . json_last_error_msg());
        }
        
        if (isset($resultado['items']) && is_array($resultado['items'])) {
            Log::info('Items encontrados: ' . count($resultado['items']));
            return $resultado['items'];
        }
        
        if (is_array($resultado) && !isset($resultado['items'])) {
            Log::info('Resultado sin items, devolviendo array completo');
            return $resultado;
        }
        
        Log::warning('No se encontraron items en la respuesta');
        return [];
    }

    private function arrayToCsv($rows)
    {
        $csv = '';
        $contador = 0;
        
        foreach ($rows as $row) {
            if ($contador >= 100) break;
            
            $cleanRow = array_map(function($cell) {
                return trim($cell ?? '');
            }, $row);
            
            if (empty(array_filter($cleanRow))) {
                continue;
            }
            
            $csv .= implode(',', array_map(function($cell) {
                return '"' . str_replace('"', '""', $cell) . '"';
            }, $cleanRow)) . "\n";
            $contador++;
        }
        
        return $csv;
    }
}