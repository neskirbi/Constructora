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
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
                'c.id',
                'c.consecutivo',
                'c.empresa',
                'c.contrato_no',
                'c.frente',
                'c.gerencia',
                'c.cliente',
                'c.obra',
                'c.lugar',
                'c.subtotal as contrato_subtotal',
                'c.iva as contrato_iva',
                'c.total as contrato_total',
                'c.monto_anticipo',
                'c.duracion',
                'c.fecha_inicio_obra',
                'c.fecha_terminacion_obra',
                'c.observaciones'
            )
            ->whereBetween('c.fecha_contrato', [$this->fechaInicio, $this->fechaFin])
            ->orderBy('c.fecha_contrato', 'asc')
            ->orderBy('c.consecutivo', 'asc');

        if ($this->contratoId) {
            $query->where('c.id', $this->contratoId);
        }

        $contratos = $query->get();

        foreach ($contratos as $contrato) {
            $ampliaciones = DB::table('ampliacionesmonto')
                ->where('id_contrato', $contrato->id)
                ->select(
                    DB::raw('COALESCE(SUM(subtotal), 0) as total_subtotal'),
                    DB::raw('COALESCE(SUM(iva), 0) as total_iva'),
                    DB::raw('COALESCE(SUM(total), 0) as total_total')
                )
                ->first();

            $contrato->importe_ampliacion = $ampliaciones->total_subtotal ?? 0;
            $contrato->iva_ampliacion = $ampliaciones->total_iva ?? 0;
            $contrato->total_ampliacion = $ampliaciones->total_total ?? 0;
            
            $contrato->importe_suma = ($contrato->contrato_subtotal ?? 0) + ($contrato->importe_ampliacion ?? 0);
            $contrato->iva_suma = ($contrato->contrato_iva ?? 0) + ($contrato->iva_ampliacion ?? 0);
            $contrato->total_suma = ($contrato->contrato_total ?? 0) + ($contrato->total_ampliacion ?? 0);
        }

        return $contratos;
    }

    public function headings(): array
    {
        // Primera fila (agrupadores) - se manejará en registerEvents
        // Segunda fila (nombres de columnas)
        return [
            'CONSEC',
            'Empresa',
            'Contrato No.',
            'FRENTE',
            'Gerencia',
            'Cliente',
            'Obra',
            'Lugar',
            'Importe',
            'IVA',
            'Total',
            'Importe',
            'IVA',
            'Total',
            'Importe',
            'IVA',
            'Total',
            'Anticipo',
            'Duración',
            'Inicio de Obra',
            'Terminación',
            'Observación'
        ];
    }

    public function map($contrato): array
    {
        return [
            $contrato->consecutivo ?? '',
            $contrato->empresa ?? '',
            $contrato->contrato_no ?? '',
            $contrato->frente ?? '',
            $contrato->gerencia ?? '',
            $contrato->cliente ?? '',
            $contrato->obra ?? '',
            $contrato->lugar ?? '',
            $contrato->contrato_subtotal ?? 0,
            $contrato->contrato_iva ?? 0,
            $contrato->contrato_total ?? 0,
            $contrato->importe_ampliacion ?? 0,
            $contrato->iva_ampliacion ?? 0,
            $contrato->total_ampliacion ?? 0,
            $contrato->importe_suma ?? 0,
            $contrato->iva_suma ?? 0,
            $contrato->total_suma ?? 0,
            $contrato->monto_anticipo ?? 0,
            $contrato->duracion ?? '',
            $contrato->fecha_inicio_obra ? date('d/m/Y', strtotime($contrato->fecha_inicio_obra)) : '',
            $contrato->fecha_terminacion_obra ? date('d/m/Y', strtotime($contrato->fecha_terminacion_obra)) : '',
            $contrato->observaciones ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            2 => [ // Segunda fila (encabezados de columna)
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
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => '"$"#,##0.00',
            'J' => '"$"#,##0.00',
            'K' => '"$"#,##0.00',
            'L' => '"$"#,##0.00',
            'M' => '"$"#,##0.00',
            'N' => '"$"#,##0.00',
            'O' => '"$"#,##0.00',
            'P' => '"$"#,##0.00',
            'Q' => '"$"#,##0.00',
            'R' => '"$"#,##0.00',
            'S' => '@',
            'T' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'U' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'V' => '@',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Insertar una nueva fila al inicio para los títulos agrupadores
                $sheet->insertNewRowBefore(1, 1);
                
                // Escribir los títulos agrupadores en la primera fila
                // Columnas A-H no tienen agrupador (se dejan vacías o se combinan?)
                $sheet->setCellValue('A1', '');
                $sheet->setCellValue('B1', '');
                $sheet->setCellValue('C1', '');
                $sheet->setCellValue('D1', '');
                $sheet->setCellValue('E1', '');
                $sheet->setCellValue('F1', '');
                $sheet->setCellValue('G1', '');
                $sheet->setCellValue('H1', '');
                
                // TOTAL CONTRATO (columnas I, J, K)
                $sheet->mergeCells('I1:K1');
                $sheet->setCellValue('I1', 'TOTAL CONTRATO');
                
                // CONVENIO AMPLIACION (columnas L, M, N)
                $sheet->mergeCells('L1:N1');
                $sheet->setCellValue('L1', 'CONVENIO AMPLIACION');
                
                // TOTAL A COBRAR (columnas O, P, Q)
                $sheet->mergeCells('O1:Q1');
                $sheet->setCellValue('O1', 'TOTAL A COBRAR');
                
                // Columnas R en adelante sin agrupador
                $sheet->setCellValue('R1', '');
                $sheet->setCellValue('S1', '');
                $sheet->setCellValue('T1', '');
                $sheet->setCellValue('U1', '');
                $sheet->setCellValue('V1', '');
                
                // Estilo para la primera fila (títulos agrupadores)
                $sheet->getStyle('A1:V1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '5B9BD5'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Limitar altura de filas a 300 px máximo
                $highestRow = $sheet->getHighestRow();
                for ($row = 1; $row <= $highestRow; $row++) {
                    $currentHeight = $sheet->getRowDimension($row)->getRowHeight();
                    if ($currentHeight > 300) {
                        $sheet->getRowDimension($row)->setRowHeight(300);
                    }
                    // Habilitar wrap text para que el contenido no se desborde
                    $sheet->getStyle('A' . $row . ':V' . $row)->getAlignment()->setWrapText(true);
                }
                
                $lastRow = $sheet->getHighestRow();
                $lastColumn = 'V';
                
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
                $sheet->getStyle('A3:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => ['size' => 11],
                ]);
                
                // Alineaciones específicas
                $sheet->getStyle('A3:A' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('T3:U' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('I3:R' . $lastRow)->getAlignment()->setHorizontal('right');
                
                // Encabezados centrados
                $sheet->getStyle('A2:V2')->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension('2')->setRowHeight(25);
                
                // Zebra striping
                for ($row = 3; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F2F2F2'],
                            ],
                        ]);
                    }
                }
                
                // Congelar paneles (primeras dos filas y primera columna)
                $sheet->freezePane('B3');
            },
        ];
    }
}