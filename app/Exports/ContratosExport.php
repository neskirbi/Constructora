<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
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
            $this->getDateOnly($contrato->fecha_inicio_obra),      // Solo fecha, sin formato
            $this->getDateOnly($contrato->fecha_terminacion_obra), // Solo fecha, sin formato
            $contrato->observaciones ?? '',
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
            return $date;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            2 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
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
            'I' => '#,##0.00',  // Importe (sin símbolo de moneda)
            'J' => '#,##0.00',  // IVA
            'K' => '#,##0.00',  // Total
            'L' => '#,##0.00',  // Importe Ampliación
            'M' => '#,##0.00',  // IVA Ampliación
            'N' => '#,##0.00',  // Total Ampliación
            'O' => '#,##0.00',  // Importe Suma
            'P' => '#,##0.00',  // IVA Suma
            'Q' => '#,##0.00',  // Total Suma
            'R' => '#,##0.00',  // Anticipo
            'T' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Inicio de Obra
            'U' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Terminación
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                $sheet->insertNewRowBefore(1, 1);
                
                $sheet->setCellValue('A1', '');
                $sheet->setCellValue('B1', '');
                $sheet->setCellValue('C1', '');
                $sheet->setCellValue('D1', '');
                $sheet->setCellValue('E1', '');
                $sheet->setCellValue('F1', '');
                $sheet->setCellValue('G1', '');
                $sheet->setCellValue('H1', '');
                
                $sheet->mergeCells('I1:K1');
                $sheet->setCellValue('I1', 'TOTAL CONTRATO');
                
                $sheet->mergeCells('L1:N1');
                $sheet->setCellValue('L1', 'CONVENIO AMPLIACION');
                
                $sheet->mergeCells('O1:Q1');
                $sheet->setCellValue('O1', 'TOTAL A COBRAR');
                
                $sheet->setCellValue('R1', '');
                $sheet->setCellValue('S1', '');
                $sheet->setCellValue('T1', '');
                $sheet->setCellValue('U1', '');
                $sheet->setCellValue('V1', '');
                
                $sheet->getStyle('A1:V1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '5B9BD5'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                $highestRow = $sheet->getHighestRow();
                for ($row = 1; $row <= $highestRow; $row++) {
                    $currentHeight = $sheet->getRowDimension($row)->getRowHeight();
                    if ($currentHeight > 300) {
                        $sheet->getRowDimension($row)->setRowHeight(300);
                    }
                    $sheet->getStyle('A' . $row . ':V' . $row)->getAlignment()->setWrapText(true);
                }
                
                $lastRow = $sheet->getHighestRow();
                $lastColumn = 'V';
                
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                
                $sheet->getStyle('A3:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => ['size' => 11],
                ]);
                
                $sheet->getStyle('A3:A' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('T3:U' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('I3:R' . $lastRow)->getAlignment()->setHorizontal('right');
                
                $sheet->getStyle('A2:V2')->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension('2')->setRowHeight(25);
                
                for ($row = 3; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F2F2F2'],
                            ],
                        ]);
                    }
                }
                
                $sheet->freezePane('B3');
            },
        ];
    }
}