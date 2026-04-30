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
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class IngresosExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents, ShouldAutoSize
{
    protected $fechaDesde;
    protected $fechaHasta;
    protected $idContrato;
    
    public function __construct($fechaDesde, $fechaHasta, $idContrato)
    {
        $this->fechaDesde = $fechaDesde;
        $this->fechaHasta = $fechaHasta;
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
                // CONTRATOS
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
                
                // AMPLIACIONES
                'ampliacionesmonto.total as convenio_aplicacion_monto',
                
                // INGRESOS
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
                'ingresos.por_cobrar',
                'ingresos.por_estimar',
                'ingresos.status',
                'ingresos.estimado_menos_deducciones'
            )
            ->whereBetween('ingresos.created_at', [$this->fechaDesde, $this->fechaHasta])
            ->orderBy('contratos.consecutivo')      // Primero por Número de Contrato
            ->orderBy('ingresos.created_at');        // Luego por fecha de creación
        
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
            'Liquido a cobrar',
            'Liquido Cobrado',
            'Fecha Cobro',
            'POR COBRAR',
            'POR FACTURAR',
            'Por Cobrar',
            'Por Estimar',
            'Status'
        ];
    }
    
    public function map($row): array
    {
        // Total a cobrar contrato c/IVA = Importe Contrato + Convenio Aplicación
        $totalACobrar = $row->importe_contrato + ($row->convenio_aplicacion_monto ?? 0);
        
        // Total facturado = Total Estimacion con IVA
        $totalFacturado = $row->total_estimacion_con_iva;
        
        // POR FACTURAR = Total a cobrar (contrato + ampliaciones) - Total facturado
        $porFacturar = $totalACobrar - $totalFacturado;
        
        // Importe Facturado = Total Estimacion con IVA - Total Deducciones
        $importeFacturado = $row->total_estimacion_con_iva - $row->total_deducciones;
        
        return [
            $row->n_obra ?? '',
            $row->empresa ?? '',
            $row->numero_contrato ?? '',
            $row->descripcion_segun_contrato ?? '',
            $row->referencia_interna ?? '',
            $row->cliente ?? '',
            'Ingresos',
            $this->formatDate($row->fecha_contrato),
            $this->formatDate($row->fecha_inicio_obra),
            $this->formatDate($row->fecha_terminacion_obra),
            $this->formatNumber($row->monto_anticipo),
            $this->formatNumber($row->importe_contrato),
            $this->formatNumber($row->convenio_aplicacion_monto),
            $this->formatNumber($totalACobrar),
            $row->no_estimacion ?? '',
            $this->formatDate($row->periodo_del),
            $this->formatDate($row->periodo_al),
            $row->n_factura ?? '',
            $this->formatDate($row->fecha_factura),
            $this->formatNumber($row->importe_estimacion),
            $this->formatPercent($row->iva),   // Muestra el porcentaje (ej. 16.00)
            $this->formatNumber($row->total_estimacion_con_iva),
            $this->formatDate($row->fecha_elaboracion),
            $this->formatNumber($row->cargos_adicionales_3_5),
            $this->formatNumber($row->retencion_5_al_millar),
            $this->formatNumber($row->sancion_atrazo_presentacion_estimacion),
            $this->formatNumber($row->sancion_atraso_de_obra),
            $this->formatNumber($row->sancion_por_obra_mal_ejecutada),
            $this->formatNumber($row->retencion_por_atraso_en_programa_obra),
            $this->formatNumber($row->amortizacion_anticipo),
            $this->formatNumber($row->amortizacion_con_iva),
            $this->formatNumber($row->total_deducciones),
            $this->formatNumber($importeFacturado),
            $this->formatNumber($row->liquido_a_cobrar),
            $this->formatNumber($row->liquido_cobrado),
            $this->formatDate($row->fecha_cobro),
            $this->formatNumber($row->por_cobrar),
            $this->formatNumber($porFacturar),
            $this->formatNumber($row->por_cobrar),
            $this->formatNumber($row->por_estimar),
            $row->status ?? '',
        ];
    }
    
    private function formatDate($date)
    {
        if (!$date) return '';
        try {
            return date('d/m/Y', strtotime($date));
        } catch (\Exception $e) {
            return $date;
        }
    }
    
    private function formatNumber($number)
    {
        return $number ? number_format((float)$number, 2) : '0.00';
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
            'AJ' => 22, 'AK' => 22, 'AL' => 15, 'AM' => 15, 'AN' => 15,
            'AO' => 15
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Estilo para encabezados (41 columnas: A a AO)
        $sheet->getStyle('A1:AO1')->applyFromArray([
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
        
        // Formato moneda para columnas numéricas
        $currencyColumns = ['K', 'L', 'M', 'N', 'T', 'U', 'V', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN'];
        foreach ($currencyColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . '1000')
                  ->getNumberFormat()
                  ->setFormatCode('$#,##0.00');
        }
        
        // Formato fecha
        $dateColumns = ['H', 'I', 'J', 'P', 'Q', 'S', 'W', 'AJ'];
        foreach ($dateColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . '1000')
                  ->getNumberFormat()
                  ->setFormatCode('dd/mm/yyyy');
        }
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->freezePane('A2');
                $event->sheet->setAutoFilter('A1:AO1');
            },
        ];
    }

    private function formatPercent($number)
    {
        return $number ? number_format((float)$number, 2) : '0.00';
    }
}