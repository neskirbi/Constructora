<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DestajosExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize, 
    WithStyles,
    WithEvents,
    WithColumnFormatting
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $contratoId;

    public function __construct($fechaInicio, $fechaFin, $contratoId = null)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->contratoId = $contratoId;
    }

    public function collection()
    {
        $query = DB::table('destajos as d')
            ->select(
                'd.consecutivo',
                'd.created_at',
                'd.clave_concepto',
                'd.descripcion_concepto',
                'd.unidad_concepto',
                'd.costo_unitario_concepto',
                'd.cantidad',
                'd.referencia',
                'd.costo_operado',
                'd.iva',
                'd.total',
                'c.refinterna',
                'c.frente',
                'p.clave as clave_proveedor',
                'p.nombre as nombre_proveedor',
                'p.clasificacion'
            )
            ->leftJoin('contratos as c', 'd.id_contrato', '=', 'c.id')
            ->leftJoin('proveedores_servicios as p', 'd.id_proveedor', '=', 'p.id')
            ->whereBetween('d.created_at', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59'])
            ->orderBy('d.created_at', 'asc');

        if ($this->contratoId) {
            $query->where('d.id_contrato', $this->contratoId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'CONSECUTIVO',
            'FECHA',
            'NO DE OBRA',
            'FRENTE',
            'CLAVE PROVEEDOR',
            'TIPO DE PROVEEDOR',
            'NOMBRE PROVEEDOR',
            'CLAVE DE CONCEPTO',
            'DESCRIPCIÓN DEL CONCEPTO',
            'UNIDAD DEL CONCEPTO',
            'COSTO UNITARIO',
            'CANTIDAD',
            'REFERENCIA',
            'COSTO OPERADO',
            'IVA',
            'TOTAL'
        ];
    }

    public function map($destajo): array
    {
        return [
            $destajo->consecutivo ?? '',
            $destajo->created_at ? date('d/m/Y', strtotime($destajo->created_at)) : '',
            $destajo->refinterna ?? '',
            $destajo->frente ?? '',
            $destajo->clave_proveedor ?? '',
            'Destajo',
            $destajo->nombre_proveedor ?? '',
            $destajo->clave_concepto ?? '',
            $destajo->descripcion_concepto ?? '',
            $destajo->unidad_concepto ?? '',
            $destajo->costo_unitario_concepto ?? 0,
            $destajo->cantidad ?? 0,
            $destajo->referencia ?? '',
            $destajo->costo_operado ?? 0,
            $destajo->iva ?? 0,
            $destajo->total ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para el encabezado
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'K' => '"$"#,##0.00', // Costo unitario
            'L' => '#,##0.00', // Cantidad
            'N' => '"$"#,##0.00', // Costo operado
            'O' => '"$"#,##0.00', // IVA
            'P' => '"$"#,##0.00', // Total
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Estilo para toda la tabla
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Estilo para las filas de datos
                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'size' => 11,
                    ],
                ]);

                // Alineación específica por columnas
                // Columnas de texto a la izquierda
                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('left'); // Consecutivo
                $sheet->getStyle('C2:G' . $lastRow)->getAlignment()->setHorizontal('left'); // Obra, Frente, Proveedor
                $sheet->getStyle('H2:J' . $lastRow)->getAlignment()->setHorizontal('left'); // Concepto
                $sheet->getStyle('M2:M' . $lastRow)->getAlignment()->setHorizontal('left'); // Referencia

                // Columnas numéricas a la derecha
                $sheet->getStyle('K2:L' . $lastRow)->getAlignment()->setHorizontal('right');
                $sheet->getStyle('N2:P' . $lastRow)->getAlignment()->setHorizontal('right');

                // Fecha centrada
                $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setHorizontal('center');

                // Encabezado centrado
                $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal('center');

                // Alto de fila para el encabezado
                $sheet->getRowDimension('1')->setRowHeight(25);

                // Alternar colores de filas (zebra striping)
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F2F2F2'],
                            ],
                        ]);
                    }
                }

                // Fecha de filtro en el encabezado (opcional)
                $sheet->setCellValue('A' . ($lastRow + 2), 'Filtro aplicado:');
                $sheet->setCellValue('B' . ($lastRow + 2), $this->fechaInicio . ' - ' . $this->fechaFin);
                $sheet->getStyle('A' . ($lastRow + 2) . ':B' . ($lastRow + 2))->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                // Total general
                $sheet->setCellValue('O' . ($lastRow + 2), 'TOTAL GENERAL:');
                $sheet->setCellValue('P' . ($lastRow + 2), '=SUM(P2:P' . $lastRow . ')');
                $sheet->getStyle('O' . ($lastRow + 2) . ':P' . ($lastRow + 2))->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFD700'],
                    ],
                ]);
                $sheet->getStyle('P' . ($lastRow + 2))->getNumberFormat()->setFormatCode('"$"#,##0.00');

                // Congelar paneles
                $sheet->freezePane('A2');
            },
        ];
    }
}