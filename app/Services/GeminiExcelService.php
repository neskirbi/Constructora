<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class GeminiExcelService
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent';
    protected $logs = [];

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        
        if (empty($this->apiKey)) {
            throw new \Exception('GEMINI_API_KEY no está configurada en el archivo .env');
        }
    }

    public function procesarExcel($archivoExcel)
    {
        $this->logs = [];
        $this->addLog('=== INICIO GEMINI ===');
        
        // Leer el Excel
        $data = Excel::toArray([], $archivoExcel);
        $rows = isset($data[1]) ? $data[1] : $data[0];
        
        $this->addLog('Filas leídas: ' . count($rows));
        
        // Limitar filas
        $rows = array_slice($rows, 0, 100);
        
        // Convertir a CSV
        $csv = $this->arrayToCsv($rows);
        
        $this->addLog('CSV generado, longitud: ' . strlen($csv));
        
        // Enviar a Gemini
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Eres un asistente que extrae datos de Excel. Analiza el archivo y devuelve SOLO un JSON válido.

                            El JSON debe tener esta estructura:
                            {
                                \"no_obra\": \"495.01\",
                                \"contratista\": \"ALEJANDRO VILLA LOPEZ\",
                                \"fecha_entrega\": \"2025-01-15\",
                                \"items\": [
                                    {
                                        \"clave\": \"CABLE-8\",
                                        \"cantidad\": 1,
                                        \"unidad\": \"CAJA\",
                                        \"link\": \"http://...\",
                                        \"observaciones\": \"INSTALACION ELECTRICA\",
                                        \"partida\": \"ELECTRICIDAD\"
                                    }
                                ]
                            }

                            REGLAS:
                            1. Busca el número de obra (etiqueta: No Obra)
                            2. Busca el contratista
                            3. Busca la fecha de entrega (etiqueta: Fecha de entrega)
                            4. Los items están en la tabla con columnas: Clave, Cantidad, Unidad, Partida, Link, Observaciones
                            5. Cada item debe tener su partida individual
                            6. Devuelve SOLO el JSON, sin texto adicional

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

        $this->addLog('Respuesta de Gemini - Status: ' . $response->status());

        if (!$response->successful()) {
            $this->addLog('Error en Gemini: ' . $response->body());
            throw new \Exception('Error en la API de Gemini: ' . $response->body());
        }

        $data = $response->json();
        $this->addLog('Respuesta recibida correctamente');
        
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $this->addLog('Texto extraído: ' . $text);
        
        // Extraer JSON del texto
        preg_match('/\{.*\}/s', $text, $matches);
        $jsonText = $matches[0] ?? $text;
        
        $this->addLog('JSON extraído: ' . $jsonText);
        
        $resultado = json_decode($jsonText, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->addLog('Error al decodificar JSON: ' . json_last_error_msg());
            throw new \Exception('Error al decodificar JSON: ' . json_last_error_msg());
        }
        
        $this->addLog('=== FIN GEMINI ===');
        
        return $resultado;
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

    private function addLog($mensaje)
    {
        $this->logs[] = $mensaje;
        Log::info($mensaje);
    }

    public function getLogs()
    {
        return $this->logs;
    }
}