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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
            ->select(
                // Campos de contrato
                'contratos.obra',
                'contratos.empresa',
                'contratos.contrato_no',
                'contratos.cliente',
                'contratos.fecha_contrato',
                'contratos.fecha_inicio_obra as inicio_de_obra',
                'contratos.fecha_terminacion_obra as terminacion_de_obra',
                'contratos.monto_anticipo as anticipo',
                'contratos.total as importe_contrato',
                'contratos.no_cuenta as n_cuenta',
                'contratos.sucursal',
                'contratos.clabe_interbancaria',
                
                // Campos de ingresos
                'ingresos.no_estimacion as estimacion',
                'ingresos.periodo_del',
                'ingresos.periodo_al',
                'ingresos.factura',
                'ingresos.fecha_factura',
                'ingresos.importe_estimacion as importe_de_estimacion',
                'ingresos.iva',
                'ingresos.retenciones_o_sanciones',
                'ingresos.total_estimacion_con_iva',
                'ingresos.avance_obra_estimacion',
                'ingresos.avance_obra_real',
                'ingresos.porcentaje_avance_financiero',
                'ingresos.cargos_adicionales_3_5 as cargos_adicionales_35_porciento',
                'ingresos.retencion_5_al_millar',
                'ingresos.sancion_atrazo_presentacion_estimacion',
                'ingresos.sancion_atraso_de_obra',
                'ingresos.sancion_por_obra_mal_ejecutada',
                'ingresos.retencion_por_atraso_en_programa_obra',
                'ingresos.amortizacion_anticipo',
                'ingresos.amortizacion_con_iva',
                'ingresos.total_deducciones',
                'ingresos.importe_facturado',
                'ingresos.liquido_a_cobrar',
                'ingresos.liquido_cobrado',
                'ingresos.fecha_cobro',
                'ingresos.por_cobrar',
                'ingresos.por_facturar',
                'ingresos.estimado_menos_deducciones',
                'ingresos.verificado'
            )
            ->whereBetween('ingresos.created_at', [$this->fechaDesde, $this->fechaHasta])
            ->orderBy('ingresos.created_at');
        
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
            'Cliente',
            'Fecha Firma de Contrato',
            'Fecha Inicio de Obra',
            'Fecha Terminación de Obra',
            'Importe de Anticipo c/IVA',
            'Importe de Contrato c/IVA',
            'Total a cobrar contrato c/IVA',
            '# Estimación',
            'del',
            'al',
            'Factura',
            'Fecha Factura',
            'Importe de Estimación',
            'I.V.A.',
            'Retenciones o sanciones',
            'Total Estimacion con IVA',
            'Estimación',
            'Real',
            '% Avance Financiero',
            '3.5 % Cargos Adicionales',
            'Retencion 5 al millar',
            'Sancion atrazo presentacion estimacion',
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
            'Estimado menos Deducciones',
            'Nº Cuenta',
            'Sucursal',
            'Clabe Interbancaria',
            'Estado Verificación'
        ];
    }
    
    public function map($ingreso): array
    {
        $estadoVerificado = $ingreso->verificado == '2' ? 'Verificado' : 
                           ($ingreso->verificado == '0' ? 'Rechazado' : 'Pendiente');
        
        return [
            $ingreso->obra ?? '',
            $ingreso->empresa ?? '',
            $ingreso->contrato_no ?? '',
            $ingreso->obra ?? '',
            $ingreso->cliente ?? '',
            $this->formatDate($ingreso->fecha_contrato),
            $this->formatDate($ingreso->inicio_de_obra),
            $this->formatDate($ingreso->terminacion_de_obra),
            $this->formatNumber($ingreso->anticipo),
            $this->formatNumber($ingreso->importe_contrato),
            $this->formatNumber($ingreso->importe_contrato),
            $ingreso->estimacion ?? '',
            $this->formatDate($ingreso->periodo_del),
            $this->formatDate($ingreso->periodo_al),
            $ingreso->factura ?? '',
            $this->formatDate($ingreso->fecha_factura),
            $this->formatNumber($ingreso->importe_de_estimacion),
            $this->formatNumber($ingreso->iva),
            $this->formatNumber($ingreso->retenciones_o_sanciones),
            $this->formatNumber($ingreso->total_estimacion_con_iva),
            $this->formatNumber($ingreso->avance_obra_estimacion),
            $this->formatNumber($ingreso->avance_obra_real),
            $this->formatPercent($ingreso->porcentaje_avance_financiero),
            $this->formatNumber($ingreso->cargos_adicionales_35_porciento),
            $this->formatNumber($ingreso->retencion_5_al_millar),
            $this->formatNumber($ingreso->sancion_atrazo_presentacion_estimacion),
            $this->formatNumber($ingreso->sancion_atraso_de_obra),
            $this->formatNumber($ingreso->sancion_por_obra_mal_ejecutada),
            $this->formatNumber($ingreso->retencion_por_atraso_en_programa_obra),
            $this->formatNumber($ingreso->amortizacion_anticipo),
            $this->formatNumber($ingreso->amortizacion_con_iva),
            $this->formatNumber($ingreso->total_deducciones),
            $this->formatNumber($ingreso->importe_facturado),
            $this->formatNumber($ingreso->liquido_a_cobrar),
            $this->formatNumber($ingreso->liquido_cobrado),
            $this->formatDate($ingreso->fecha_cobro),
            $this->formatNumber($ingreso->por_cobrar),
            $this->formatNumber($ingreso->por_facturar),
            $this->formatNumber($ingreso->estimado_menos_deducciones),
            $ingreso->n_cuenta ?? '',
            $ingreso->sucursal ?? '',
            $ingreso->clabe_interbancaria ?? '',
            $estadoVerificado
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
        return $number ? number_format($number, 2) : '0.00';
    }
    
    private function formatPercent($number)
    {
        return $number ? number_format($number / 100, 4) : '0.0000';
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 30, 'B' => 25, 'C' => 20, 'D' => 35, 'E' => 30, 'F' => 18,
            'G' => 18, 'H' => 18, 'I' => 22, 'J' => 22, 'K' => 22, 'L' => 15,
            'M' => 12, 'N' => 12, 'O' => 18, 'P' => 15, 'Q' => 22, 'R' => 15,
            'S' => 22, 'T' => 22, 'U' => 15, 'V' => 15, 'W' => 18, 'X' => 22,
            'Y' => 22, 'Z' => 25, 'AA' => 22, 'AB' => 25, 'AC' => 22, 'AD' => 22,
            'AE' => 22, 'AF' => 22, 'AG' => 22, 'AH' => 22, 'AI' => 22, 'AJ' => 22,
            'AK' => 22, 'AL' => 22, 'AM' => 22, 'AN' => 22, 'AO' => 22, 'AP' => 15,
            'AQ' => 15, 'AR' => 20
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Encabezados con estilo
        $sheet->getStyle('A1:AR1')->applyFromArray([
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
        
        // Altura de fila para encabezados
        $sheet->getRowDimension(1)->setRowHeight(40);
        
        // Estilo para todas las celdas
        $sheet->getStyle('A2:AR1000')->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Altura automática para filas
        for ($i = 2; $i <= 1000; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }
        
        // Formato para columnas de fechas
        $dateColumns = ['F', 'G', 'H', 'M', 'N', 'P', 'AG'];
        foreach ($dateColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . '1000')
                  ->getNumberFormat()
                  ->setFormatCode('dd/mm/yyyy');
        }
        
        // Formato para columnas de moneda
        $currencyColumns = ['I', 'J', 'K', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM'];
        foreach ($currencyColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . '1000')
                  ->getNumberFormat()
                  ->setFormatCode('#,##0.00');
        }
        
        // Formato para porcentaje
        $sheet->getStyle('W2:W1000')
              ->getNumberFormat()
              ->setFormatCode('0.00%');
        
        // Alternar colores de filas para mejor lectura
        $sheet->getStyle('A2:AR1000')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2'],
            ],
        ]);
        
        // Fila impar diferente color
        $sheet->getStyle('A3:AR3')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF'],
            ],
        ]);
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Congelar paneles (fijar encabezados)
                $event->sheet->freezePane('A2');
                
                // Añadir filtros automáticos
                $event->sheet->setAutoFilter('A1:AR1');
                
                // Ajustar automáticamente el ancho de columnas basado en contenido
                foreach (range('A', 'AR') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(false);
                }
                
                // Ajustar algunas columnas específicas
                $event->sheet->getColumnDimension('D')->setWidth(40); // Descripción
                $event->sheet->getColumnDimension('E')->setWidth(35); // Cliente
                $event->sheet->getColumnDimension('Z')->setWidth(30); // Sanciones largas
                
                // Añadir bordes exteriores gruesos
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                
                $event->sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}