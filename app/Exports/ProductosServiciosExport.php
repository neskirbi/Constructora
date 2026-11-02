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
use Illuminate\Support\Facades\DB;

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
    protected $proveedores;
    protected $contratos;

    public function __construct($productos)
    {
        $this->productos = $productos;

        // Proveedores y contratos indexados por id
        $this->proveedores = DB::table('proveedores_servicios')
            ->select('id', 'clave', 'nombre')
            ->get()
            ->keyBy('id');

        $this->contratos = DB::table('contratos')
            ->select('id', 'consecutivo', 'refinterna')
            ->get()
            ->keyBy('id');
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
            'PROVEEDOR',
            'CONTRATO',
            'ESTATUS',
            'PRECIO',
            'ÚLTIMO COSTO',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
        ];
    }

    public function map($producto): array
    {
        $descripcion = $this->ajustarTexto($producto->descripcion ?? '', 60);

        // Proveedor
        $proveedor = '';
        if ($producto->id_proveedor && isset($this->proveedores[$producto->id_proveedor])) {
            $p = $this->proveedores[$producto->id_proveedor];
            $proveedor = trim(($p->clave ?? '') . ' - ' . ($p->nombre ?? ''));
        }

        // Contrato
        $contrato = '';
        if ($producto->id_contrato && isset($this->contratos[$producto->id_contrato])) {
            $c = $this->contratos[$producto->id_contrato];
            $contrato = trim(($c->consecutivo ?? '') . ' - ' . ($c->refinterna ?? ''));
        }

        // Estatus
        $estatus = $producto->estatus_ejecucion == 'ejecutado' ? 'Ejecutado' : 'Por ejecutar';

        return [
            $producto->clave ?? '',
            $descripcion,
            $producto->unidades ?? '',
            $proveedor,
            $contrato,
            $estatus,
            $producto->precio ?? 0,
            $producto->ult_costo ?? 0,
            $this->getDateOnly($producto->created_at),
            $this->getDateOnly($producto->updated_at),
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
    private function ajustarTexto($texto, $longitudMaxima = 60)
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
            'G' => '#,##0.00',    // Precio
            'H' => '#,##0.00',    // Último costo
            'I' => 'dd/mm/yyyy',  // Fecha creación
            'J' => 'dd/mm/yyyy',  // Fecha modificación
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->getColumnDimension('A')->setWidth(15);  // Clave
                $sheet->getColumnDimension('B')->setWidth(55);  // Descripción
                $sheet->getColumnDimension('C')->setWidth(12);  // Unidades
                $sheet->getColumnDimension('D')->setWidth(35);  // Proveedor
                $sheet->getColumnDimension('E')->setWidth(30);  // Contrato
                $sheet->getColumnDimension('F')->setWidth(15);  // Estatus
                $sheet->getColumnDimension('G')->setWidth(18);  // Precio
                $sheet->getColumnDimension('H')->setWidth(18);  // Último costo
                $sheet->getColumnDimension('I')->setWidth(18);  // Fecha creación
                $sheet->getColumnDimension('J')->setWidth(18);  // Fecha modificación

                $sheet->getStyle('B2:B' . $lastRow)->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_TOP);

                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);

                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    'font' => ['size' => 11],
                ]);

                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('F2:F' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('I2:J' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('G2:H' . $lastRow)->getAlignment()->setHorizontal('right');

                $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension('1')->setRowHeight(25);

                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                        ]);
                    }
                    $sheet->getRowDimension($row)->setRowHeight(-1);
                }

                $sheet->freezePane('A2');
            },
        ];
    }
}