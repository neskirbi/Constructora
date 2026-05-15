<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class ProductosServiciosExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize, 
    WithStyles,
    WithEvents,
    WithColumnFormatting
{
    protected $productos;

    public function __construct($productos)
    {
        $this->productos = $productos;
    }

    public function collection()
    {
        return $this->productos;
    }

    public function headings(): array
    {
        return [
            'CLAVE',
            'DESCRIPCIÓN',
            'UNIDADES',
            'ÚLTIMO COSTO',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
        ];
    }

    public function map($producto): array
    {
        // Función para ajustar texto largo con saltos de línea cada ~60 caracteres
        $descripcion = $this->ajustarTexto($producto->descripcion ?? '', 60);
        
        return [
            $producto->clave ?? '',
            $descripcion,
            $producto->unidades ?? '',
            $producto->ult_costo ?? 0,
            $producto->created_at ? Carbon::parse($producto->created_at)->format('d/m/Y H:i:s') : '',
            $producto->updated_at ? Carbon::parse($producto->updated_at)->format('d/m/Y H:i:s') : '',
        ];
    }

    /**
     * Ajusta un texto largo agregando saltos de línea cada N caracteres
     * respetando espacios en blanco
     */
    private function ajustarTexto($texto, $longitudMaxima = 60)
    {
        if (strlen($texto) <= $longitudMaxima) {
            return $texto;
        }
        
        $palabras = explode(' ', $texto);
        $lineas = [];
        $lineaActual = '';
        
        foreach ($palabras as $palabra) {
            // Si la palabra es muy larga, dividirla
            if (strlen($palabra) > $longitudMaxima) {
                if (!empty($lineaActual)) {
                    $lineas[] = $lineaActual;
                    $lineaActual = '';
                }
                $lineas[] = chunk_split($palabra, $longitudMaxima, "\n");
                continue;
            }
            
            $lineaPrueba = empty($lineaActual) ? $palabra : $lineaActual . ' ' . $palabra;
            
            if (strlen($lineaPrueba) <= $longitudMaxima) {
                $lineaActual = $lineaPrueba;
            } else {
                if (!empty($lineaActual)) {
                    $lineas[] = $lineaActual;
                }
                $lineaActual = $palabra;
            }
        }
        
        if (!empty($lineaActual)) {
            $lineas[] = $lineaActual;
        }
        
        return implode("\n", $lineas);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '#,##0.00',  // Último costo
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' HH:MM:SS',  // Fecha creación
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' HH:MM:SS',  // Fecha modificación
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Configurar ancho de columnas
                $sheet->getColumnDimension('A')->setWidth(15);  // CLAVE
                $sheet->getColumnDimension('B')->setWidth(55);  // DESCRIPCIÓN (aproximadamente 350-400px)
                $sheet->getColumnDimension('C')->setWidth(12);  // UNIDADES
                $sheet->getColumnDimension('D')->setWidth(18);  // ÚLTIMO COSTO
                $sheet->getColumnDimension('E')->setWidth(20);  // FECHA CREACIÓN
                $sheet->getColumnDimension('F')->setWidth(20);  // FECHA MODIFICACIÓN

                // Habilitar ajuste de texto para la columna DESCRIPCIÓN
                $sheet->getStyle('B2:B' . $lastRow)->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_TOP);

                // Bordes para toda la tabla
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);

                // Estilo para las celdas de datos
                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    'font' => ['size' => 11],
                ]);

                // Centrar CLAVE, UNIDADES y fechas
                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('E2:F' . $lastRow)->getAlignment()->setHorizontal('center');

                // Alinear a la derecha el ÚLTIMO COSTO
                $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setHorizontal('right');

                // Encabezado centrado y altura
                $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension('1')->setRowHeight(25);

                // Filas zebra (colores alternados)
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                        ]);
                    }
                    // Ajustar altura de fila automáticamente según el contenido
                    $sheet->getRowDimension($row)->setRowHeight(-1);
                }

                // Congelar primera fila
                $sheet->freezePane('A2');
            },
        ];
    }
}