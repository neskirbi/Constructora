<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class IngresosExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    protected $idContrato;
    
    public function __construct($idContrato)
    {
        $this->idContrato = $idContrato;
    }
    
    public function query()
    {
        $query = DB::table('ingresos')
            ->join('contratos', 'ingresos.id_contrato', '=', 'contratos.id')
            ->leftJoin('ampliacionesmonto', function($join) {
                $join->on('contratos.id', '=', 'ampliacionesmonto.id_contrato')
                     ->whereRaw('ampliacionesmonto.created_at = (SELECT MAX(created_at) FROM ampliacionesmonto WHERE id_contrato = contratos.id)');
            })
            ->select(
                'contratos.consecutivo as n_obra',
                'contratos.empresa',
                'contratos.contrato_no as numero_contrato',
                'contratos.obra as descripcion_segun_contrato',
                'contratos.refinterna as referencia_interna',
                'contratos.cliente',
                'contratos.fecha_contrato',
                'contratos.fecha_inicio_obra',
                'contratos.fecha_terminacion_obra',
                'contratos.monto_anticipo',
                'contratos.total as importe_contrato',
                'ampliacionesmonto.total as convenio_aplicacion_monto',
                'ingresos.no_estimacion',
                'ingresos.periodo_del',
                'ingresos.periodo_al',
                'ingresos.factura as n_factura',
                'ingresos.fecha_factura',
                'ingresos.importe_estimacion',
                'ingresos.importe_iva as iva',
                'ingresos.total_estimacion_con_iva',
                'ingresos.created_at as fecha_elaboracion',
                'ingresos.cargos_adicionales_3_5',
                'ingresos.retencion_5_al_millar',
                'ingresos.sancion_atrazo_presentacion_estimacion',
                'ingresos.sancion_atraso_de_obra',
                'ingresos.sancion_por_obra_mal_ejecutada',
                'ingresos.retencion_por_atraso_en_programa_obra',
                'ingresos.amortizacion_anticipo',
                'ingresos.amortizacion_iva as amortizacion_con_iva',
                'ingresos.total_deducciones',
                'ingresos.liquido_a_cobrar',
                'ingresos.liquido_cobrado',
                'ingresos.fecha_cobro',
                'ingresos.por_estimar',
                'ingresos.status',
                'ingresos.estimado_menos_deducciones'
            )
            ->orderBy('contratos.consecutivo')
            ->orderBy('ingresos.factura');
        
        if ($this->idContrato && $this->idContrato != 'todos') {
            $query->where('ingresos.id_contrato', $this->idContrato);
        }
        
        return $query;
    }
    
    public function headings(): array
    {
        return [
            'N° obra',
            'Empresa',
            'Numero de Contrato',
            'Descripcion según contrato',
            'Referencia interna',
            'Cliente',
            'AREA',
            'Fecha Firma de Contrato',
            'Fecha Inicio de Obra',
            'Fecha Terminación de Obra',
            'Importe de Anticipo c/IVA',
            'Importe de Contrato c/IVA',
            'Convenio Apliacion de monto c/IVA',
            'Total a cobrar contrato c/IVA',
            '# Estimación',
            'del',
            'al',
            'N° Factura',
            'Fecha',
            'Importe de Estimación',
            'I.V.A.',
            'Total Estimacion con IVA',
            'Fecha Elaboracion',
            '3.5 % Cargos Adicionales',
            'Retencion 5 al millar',
            'Sancion atrazo presntacion estimacion',
            'Sancion atraso de obra',
            'Sancion por obra mal ejecutada',
            'Retencion por atraso en programa de obra',
            'Amortización anticipo',
            'Amortización con I.V.A.',
            'Total deducciones',
            'Importe Facturado',
            'Líquido a cobrar',
            'Líquido Cobrado',
            'Líquido por cobrar',
            'Fecha Cobro',
            'Status'
        ];
    }
        
    public function map($row): array
    {
        $totalACobrar = $row->importe_contrato + ($row->convenio_aplicacion_monto ?? 0);
        $importeFacturado = $row->total_estimacion_con_iva - $row->total_deducciones;
        $liquidoACobrar = $row->estimado_menos_deducciones ?? 0;
        $liquidoCobrado = $row->liquido_cobrado ?? 0;
        $liquidoPorCobrar = ($row->estimado_menos_deducciones ?? 0) - ($row->liquido_cobrado ?? 0);
        
        return [
            $row->n_obra ?? '',
            $row->empresa ?? '',
            $row->numero_contrato ?? '',
            $row->descripcion_segun_contrato ?? '',
            $row->referencia_interna ?? '',
            $row->cliente ?? '',
            'Ingresos',
            $this->getDateOnly($row->fecha_contrato),    // Solo fecha, sin hora
            $this->getDateOnly($row->fecha_inicio_obra), // Solo fecha, sin hora
            $this->getDateOnly($row->fecha_terminacion_obra), // Solo fecha, sin hora
            $row->monto_anticipo ?? 0,
            $row->importe_contrato ?? 0,
            $row->convenio_aplicacion_monto ?? 0,
            $totalACobrar,
            $row->no_estimacion ?? '',
            $this->getDateOnly($row->periodo_del),       // Solo fecha, sin hora
            $this->getDateOnly($row->periodo_al),        // Solo fecha, sin hora
            $row->n_factura ?? '',
            $this->getDateOnly($row->fecha_factura),     // Solo fecha, sin hora
            $row->importe_estimacion ?? 0,
            $row->iva ?? 0,
            $row->total_estimacion_con_iva ?? 0,
            $this->getDateOnly($row->fecha_elaboracion), // Solo fecha, sin hora
            $row->cargos_adicionales_3_5 ?? 0,
            $row->retencion_5_al_millar ?? 0,
            $row->sancion_atrazo_presentacion_estimacion ?? 0,
            $row->sancion_atraso_de_obra ?? 0,
            $row->sancion_por_obra_mal_ejecutada ?? 0,
            $row->retencion_por_atraso_en_programa_obra ?? 0,
            $row->amortizacion_anticipo ?? 0,
            $row->amortizacion_con_iva ?? 0,
            $row->total_deducciones ?? 0,
            $importeFacturado,
            $liquidoACobrar,
            $liquidoCobrado,
            $liquidoPorCobrar,
            $this->getDateOnly($row->fecha_cobro),       // Solo fecha, sin hora
            $row->status ?? '',
        ];
    }
    
    /**
     * Extraer solo la fecha (sin hora) y devolverla como fecha Excel
     */
    private function getDateOnly($date)
    {
        if (!$date) return null;
        try {
            // Si es string con hora, extraer solo la parte de fecha
            if (is_string($date) && strpos($date, ' ') !== false) {
                $date = explode(' ', $date)[0]; // "2025-01-27 10:30:00" → "2025-01-27"
            }
            return $date;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Fecha Firma de Contrato
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Fecha Inicio de Obra
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Fecha Terminación de Obra
            'K' => '#,##0.00',                          // Importe de Anticipo
            'L' => '#,##0.00',                          // Importe de Contrato
            'M' => '#,##0.00',                          // Convenio Aplicación
            'N' => '#,##0.00',                          // Total a cobrar
            'P' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Periodo del
            'Q' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Periodo al
            'S' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Fecha Factura
            'T' => '#,##0.00',                          // Importe de Estimación
            'U' => '#,##0.00',                          // IVA
            'V' => '#,##0.00',                          // Total Estimación con IVA
            'W' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Fecha Elaboración
            'X' => '#,##0.00',                          // 3.5 % Cargos Adicionales
            'Y' => '#,##0.00',                          // Retención 5 al millar
            'Z' => '#,##0.00',                          // Sanción atraso presentación
            'AA' => '#,##0.00',                         // Sanción atraso de obra
            'AB' => '#,##0.00',                         // Sanción por obra mal ejecutada
            'AC' => '#,##0.00',                         // Retención por atraso
            'AD' => '#,##0.00',                         // Amortización anticipo
            'AE' => '#,##0.00',                         // Amortización con IVA
            'AF' => '#,##0.00',                         // Total deducciones
            'AG' => '#,##0.00',                         // Importe Facturado
            'AH' => '#,##0.00',                         // Líquido a cobrar
            'AI' => '#,##0.00',                         // Líquido Cobrado
            'AJ' => '#,##0.00',                         // Líquido por cobrar
            'AK' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Fecha Cobro
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15, 'B' => 30, 'C' => 20, 'D' => 40, 'E' => 20,
            'F' => 30, 'G' => 15, 'H' => 18, 'I' => 18, 'J' => 18,
            'K' => 22, 'L' => 22, 'M' => 30, 'N' => 25, 'O' => 15,
            'P' => 12, 'Q' => 12, 'R' => 18, 'S' => 15, 'T' => 22,
            'U' => 15, 'V' => 22, 'W' => 18, 'X' => 25, 'Y' => 22,
            'Z' => 30, 'AA' => 22, 'AB' => 30, 'AC' => 25, 'AD' => 22,
            'AE' => 22, 'AF' => 22, 'AG' => 22, 'AH' => 22, 'AI' => 22,
            'AJ' => 22, 'AK' => 22, 'AL' => 15, 'AM' => 15
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:AM1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        
        $sheet->getRowDimension(1)->setRowHeight(40);
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->freezePane('A2');
                $event->sheet->setAutoFilter('A1:AM1');
            },
        ];
    }
}