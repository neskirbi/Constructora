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

class ContratosExport implements 
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
        $query = DB::table('contratos as c')
            ->select(
                'c.consecutivo',
                'c.refinterna as referencia_interna',
                'c.contrato_no',
                'c.frente',
                'c.gerencia',
                'c.cliente',
                'c.obra',
                'c.lugar',
                'c.concepto',
                'c.subtotal',
                'c.iva',
                'c.total',
                'c.porcentaje_anticipo',
                'c.monto_anticipo',
                DB::raw('c.total + COALESCE(c.monto_anticipo, 0) as total_mas_anticipo'),
                'c.duracion',
                'c.fecha_contrato',
                'c.fecha_inicio_obra',
                'c.fecha_terminacion_obra',
                'c.observaciones',
                'c.razon_social',
                'c.rfc',
                'c.calle_numero',
                'c.colonia',
                'c.codigo_postal',
                'c.entidad',
                'c.alcaldia_municipio',
                'c.telefono',
                'c.banco',
                'c.no_cuenta',
                'c.sucursal',
                'c.clabe_interbancaria',
                'c.mail_facturas',
                'c.representante_legal',
                'c.created_at',
                'c.updated_at'
            )
            ->whereBetween('c.fecha_contrato', [$this->fechaInicio, $this->fechaFin])
            ->orderBy('c.fecha_contrato', 'asc')
            ->orderBy('c.consecutivo', 'asc');

        // Si hay contrato específico, filtrar por él
        if ($this->contratoId) {
            $query->where('c.id', $this->contratoId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'CONSECUTIVO',
            'REFERENCIA INTERNA',
            'CONTRATO No.',
            'FRENTE',
            'GERENCIA',
            'CLIENTE',
            'OBRA',
            'LUGAR',
            'CONCEPTO',
            'SUBTOTAL',
            'IVA',
            'TOTAL CONTRATO',
            '% ANTICIPO',
            'MONTO ANTICIPO',
            'TOTAL + ANTICIPO',
            'DURACIÓN',
            'FECHA CONTRATO',
            'FECHA INICIO OBRA',
            'FECHA TERMINACIÓN',
            'OBSERVACIONES',
            'RAZÓN SOCIAL',
            'RFC',
            'CALLE Y NÚMERO',
            'COLONIA',
            'CÓDIGO POSTAL',
            'ENTIDAD',
            'ALCALDÍA/MUNICIPIO',
            'TELÉFONO',
            'BANCO',
            'No. CUENTA',
            'SUCURSAL',
            'CLABE INTERBANCARIA',
            'EMAIL FACTURAS',
            'REPRESENTANTE LEGAL',
            'FECHA CREACIÓN',
            'FECHA ACTUALIZACIÓN'
        ];
    }

    public function map($contrato): array
    {
        return [
            $contrato->consecutivo ?? '',
            $contrato->referencia_interna ?? '',
            $contrato->contrato_no ?? '',
            $contrato->frente ?? '',
            $contrato->gerencia ?? '',
            $contrato->cliente ?? '',
            $contrato->obra ?? '',
            $contrato->lugar ?? '',
            $contrato->concepto ?? '',
            $contrato->subtotal ?? 0,
            $contrato->iva ?? 0,
            $contrato->total ?? 0,
            $contrato->porcentaje_anticipo ?? 0,
            $contrato->monto_anticipo ?? 0,
            $contrato->total_mas_anticipo ?? 0,
            $contrato->duracion ?? '',
            $contrato->fecha_contrato ? date('d/m/Y', strtotime($contrato->fecha_contrato)) : '',
            $contrato->fecha_inicio_obra ? date('d/m/Y', strtotime($contrato->fecha_inicio_obra)) : '',
            $contrato->fecha_terminacion_obra ? date('d/m/Y', strtotime($contrato->fecha_terminacion_obra)) : '',
            $contrato->observaciones ?? '',
            $contrato->razon_social ?? '',
            $contrato->rfc ?? '',
            $contrato->calle_numero ?? '',
            $contrato->colonia ?? '',
            $contrato->codigo_postal ?? '',
            $contrato->entidad ?? '',
            $contrato->alcaldia_municipio ?? '',
            $contrato->telefono ?? '',
            $contrato->banco ?? '',
            $contrato->no_cuenta ?? '',
            $contrato->sucursal ?? '',
            $contrato->clabe_interbancaria ?? '',
            $contrato->mail_facturas ?? '',
            $contrato->representante_legal ?? '',
            $contrato->created_at ? date('d/m/Y H:i', strtotime($contrato->created_at)) : '',
            $contrato->updated_at ? date('d/m/Y H:i', strtotime($contrato->updated_at)) : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
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
            'J' => '"$"#,##0.00',  // SUBTOTAL
            'K' => '"$"#,##0.00',  // IVA
            'L' => '"$"#,##0.00',  // TOTAL CONTRATO
            'M' => '0.00"% "',     // % ANTICIPO (con símbolo %)
            'N' => '"$"#,##0.00',  // MONTO ANTICIPO
            'O' => '"$"#,##0.00',  // TOTAL + ANTICIPO (columna destacada)
            'P' => '@',             // DURACIÓN (texto)
            'Q' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // FECHA CONTRATO
            'R' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // FECHA INICIO OBRA
            'S' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // FECHA TERMINACIÓN
            'AG' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' H:MM',  // FECHA CREACIÓN
            'AH' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' H:MM',  // FECHA ACTUALIZACIÓN
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Bordes para toda la tabla
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Estilo para filas de datos
                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => ['size' => 11],
                ]);

                // Alineaciones específicas
                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center'); // Consecutivo
                $sheet->getStyle('Q2:S' . $lastRow)->getAlignment()->setHorizontal('center'); // Fechas
                $sheet->getStyle('AG2:AH' . $lastRow)->getAlignment()->setHorizontal('center'); // Fechas creación
                $sheet->getStyle('J2:P' . $lastRow)->getAlignment()->setHorizontal('right');  // Números
                
                // Destacar la columna "TOTAL + ANTICIPO" con color de fondo
                $sheet->getStyle('O2:O' . $lastRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '000000'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E2F0D9'], // Verde claro
                    ],
                ]);

                // Encabezado centrado
                $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension('1')->setRowHeight(25);

                // Zebra striping (filas alternadas)
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

                // Información del filtro
                $filaInfo = $lastRow + 2;
                $sheet->setCellValue('A' . $filaInfo, 'Filtro aplicado:');
                $sheet->setCellValue('B' . $filaInfo, 'Fecha contrato: ' . $this->fechaInicio . ' - ' . $this->fechaFin);
                
                // Información del contrato específico
                if ($this->contratoId) {
                    $contrato = DB::table('contratos')->where('id', $this->contratoId)->first();
                    $sheet->setCellValue('C' . $filaInfo, 'Contrato: ' . ($contrato->refinterna ?? $contrato->contrato_no ?? ''));
                } else {
                    $sheet->setCellValue('C' . $filaInfo, 'Contrato: TODOS');
                }
                $sheet->getStyle('A' . $filaInfo . ':C' . $filaInfo)->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                // Totales generales
                $filaTotales = $filaInfo + 2;
                
                // Totales de SUBTOTAL
                $sheet->setCellValue('I' . $filaTotales, 'SUBTOTAL GENERAL:');
                $sheet->setCellValue('J' . $filaTotales, '=SUM(J2:J' . $lastRow . ')');
                
                // Totales de IVA
                $sheet->setCellValue('I' . ($filaTotales + 1), 'IVA GENERAL:');
                $sheet->setCellValue('J' . ($filaTotales + 1), '=SUM(K2:K' . $lastRow . ')');
                
                // Totales de TOTAL CONTRATO
                $sheet->setCellValue('I' . ($filaTotales + 2), 'TOTAL CONTRATOS GENERAL:');
                $sheet->setCellValue('J' . ($filaTotales + 2), '=SUM(L2:L' . $lastRow . ')');
                
                // Totales de MONTO ANTICIPO
                $sheet->setCellValue('I' . ($filaTotales + 3), 'TOTAL ANTICIPOS:');
                $sheet->setCellValue('J' . ($filaTotales + 3), '=SUM(N2:N' . $lastRow . ')');
                
                // Totales de TOTAL + ANTICIPO
                $sheet->setCellValue('I' . ($filaTotales + 4), 'TOTAL + ANTICIPO GENERAL:');
                $sheet->setCellValue('J' . ($filaTotales + 4), '=SUM(O2:O' . $lastRow . ')');
                
                // Estilo para los títulos de totales
                $sheet->getStyle('I' . $filaTotales . ':I' . ($filaTotales + 4))->applyFromArray([
                    'font' => ['bold' => true],
                ]);
                
                // Estilo para las celdas de totales
                $sheet->getStyle('J' . $filaTotales . ':J' . ($filaTotales + 4))->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFD700'], // Dorado
                    ],
                ]);
                
                // Formato moneda para los totales
                $sheet->getStyle('J' . $filaTotales . ':J' . ($filaTotales + 4))->getNumberFormat()
                    ->setFormatCode('"$"#,##0.00');

                // Congelar paneles (encabezado)
                $sheet->freezePane('A2');
            },
        ];
    }
}