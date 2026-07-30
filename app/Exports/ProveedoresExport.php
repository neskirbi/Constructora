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

class ProveedoresExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize, 
    WithStyles,
    WithEvents,
    WithColumnFormatting
{
    protected $proveedores;

    public function __construct($proveedores)
    {
        $this->proveedores = $proveedores;
    }

    public function collection()
    {
        return $this->proveedores;
    }

    public function headings(): array
    {
        return [
            'CLAVE',
            'NOMBRE',
            'TELÉFONO',
            'CLASIFICACIÓN',
            'ESPECIALIDAD',
            'CALLE',
            'ESTATUS',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
        ];
    }

    public function map($proveedor): array
    {
        $nombre = $this->ajustarTexto($proveedor->nombre ?? '', 50);
        $especialidad = $this->ajustarTexto($proveedor->especialidad ?? '', 40);
        $calle = $this->ajustarTexto($proveedor->calle ?? '', 40);

        // Forzar formato decimal con 2 decimales
        $clave = $proveedor->clave;
        if (is_numeric($clave) && strpos($clave, '.') === false) {
            $clave = number_format((float)$clave, 2, '.', '');
        }

        return [
            $clave,
            $nombre,
            $proveedor->telefono ?? '',
            $proveedor->clasificacion ?? '',
            $especialidad,
            $calle,
            $proveedor->estatus ?? '',
            $this->getDateOnly($proveedor->created_at),
            $this->getDateOnly($proveedor->updated_at),
        ];
    }

    /**
     * Extraer solo la fecha (sin hora)
     */
    private function getDateOnly($date)
    {
        if (!$date) return null;
        try {
            if (is_string($date) && strpos($date, ' ') !== false) {
                $date = explode(' ', $date)[0];
            }
            $timestamp = strtotime($date);
            if ($timestamp === false) return null;
            return ($timestamp / 86400) + 25569;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Ajusta un texto largo agregando saltos de línea cada N caracteres
     */
    private function ajustarTexto($texto, $longitudMaxima = 50)
    {
        if (strlen($texto) <= $longitudMaxima) {
            return $texto;
        }
        
        $palabras = explode(' ', $texto);
        $lineas = [];
        $lineaActual = '';
        
        foreach ($palabras as $palabra) {
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
            'A' => NumberFormat::FORMAT_NUMBER_00,      // Clave con 2 decimales
            'H' => 'dd/mm/yyyy',  // Fecha creación
            'I' => 'dd/mm/yyyy',  // Fecha modificación
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Ajustar anchos de columna
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(45);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(18);
                $sheet->getColumnDimension('E')->setWidth(35);
                $sheet->getColumnDimension('F')->setWidth(35);
                $sheet->getColumnDimension('G')->setWidth(12);
                $sheet->getColumnDimension('H')->setWidth(18);
                $sheet->getColumnDimension('I')->setWidth(18);

                // Wrap text en columnas largas
                $sheet->getStyle('B2:B' . $lastRow)->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_TOP);
                $sheet->getStyle('E2:E' . $lastRow)->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_TOP);
                $sheet->getStyle('F2:F' . $lastRow)->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_TOP);

                // Bordes
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);

                // Estilo general de datos
                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    'font' => ['size' => 11],
                ]);

                // Alineaciones específicas
                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('H2:I' . $lastRow)->getAlignment()->setHorizontal('center');

                // Encabezados
                $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension('1')->setRowHeight(25);

                // Filas zebra
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                        ]);
                    }
                    $sheet->getRowDimension($row)->setRowHeight(-1);
                }

                // Congelar paneles
                $sheet->freezePane('A2');
            },
        ];
    }
}