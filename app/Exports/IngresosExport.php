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
                'contratos.cliente',
                // AREA - OMITIDO según tu indicación
                'contratos.fecha_contrato',
                'contratos.fecha_inicio_obra as fecha_inicio_obra',
                'contratos.fecha_terminacion_obra as fecha_terminacion_obra',
                'contratos.monto_anticipo as monto_anticipo',
                'contratos.total as importe_contrato',
                // Convenio Apliacion de monto c/IVA - OMITIDO según tu indicación
                'contratos.total as total_a_cobrar_contrato',
                
                // Campos de ingresos
                'ingresos.no_estimacion as no_estimacion',
                'ingresos.periodo_del',
                'ingresos.periodo_al',
                'ingresos.factura',
                'ingresos.fecha_factura',
                'ingresos.importe_estimacion',
                'ingresos.iva',
                'ingresos.retenciones_o_sanciones',
                'ingresos.total_estimacion_con_iva',
                'ingresos.created_at as fecha_elaboracion',
                'ingresos.avance_obra_estimacion',
                'ingresos.avance_obra_real',
                'ingresos.porcentaje_avance_financiero',
                'ingresos.cargos_adicionales_3_5',
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
                // 'Por Cobrar' segunda vez - OMITIDO (solo una vez)
                'ingresos.por_estimar',
                'ingresos.status',
                'ingresos.estimado_menos_deducciones',
                'contratos.no_cuenta',
                'contratos.sucursal',
                'contratos.clabe_interbancaria'
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
            'Cliente',
            // 'AREA' - OMITIDO
            'Fecha Firma de Contrato',
            'Fecha Inicio de Obra',
            'Fecha Terminación de Obra',
            'Importe de Anticipo c/IVA',
            'Importe de Contrato c/IVA',
            'Convenio Apliacion de monto c/IVA', // Columna vacía
            'Total a cobrar contrato c/IVA',
            '# Estimación',
            'del',
            'al',
            'Factura',
            'Fecha', // Fecha Factura
            'Importe de Estimación',
            'I.V.A.',
            'Retenciones o sanciones',
            'Total Estimacion con IVA',
            'Fecha Elaboracion',
            'Estimación',
            'Real',
            '% Avance Financiero',
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
            // 'Por Cobrar' - OMITIDO (segunda aparición)
            'Por Estimar',
            'Status',
            'Estimado menos Deducciones',
            'Nº Cuenta',
            'Sucursal',
            'Clabe Interbancaria'
        ];
    }
    
    public function map($ingreso): array
    {
        return [
            // Columna 1: Cliente
            $ingreso->cliente ?? '',
            
            // Columna 2: Fecha Firma de Contrato (AREA omitida)
            $this->formatDate($ingreso->fecha_contrato),
            
            // Columna 3: Fecha Inicio de Obra
            $this->formatDate($ingreso->fecha_inicio_obra),
            
            // Columna 4: Fecha Terminación de Obra
            $this->formatDate($ingreso->fecha_terminacion_obra),
            
            // Columna 5: Importe de Anticipo c/IVA
            $this->formatNumber($ingreso->monto_anticipo),
            
            // Columna 6: Importe de Contrato c/IVA
            $this->formatNumber($ingreso->importe_contrato),
            
            // Columna 7: Convenio Apliacion de monto c/IVA (vacío)
            '', // Campo no existe en la base de datos
            
            // Columna 8: Total a cobrar contrato c/IVA
            $this->formatNumber($ingreso->total_a_cobrar_contrato),
            
            // Columna 9: # Estimación
            $ingreso->no_estimacion ?? '',
            
            // Columna 10: del
            $this->formatDate($ingreso->periodo_del),
            
            // Columna 11: al
            $this->formatDate($ingreso->periodo_al),
            
            // Columna 12: Factura
            $ingreso->factura ?? '',
            
            // Columna 13: Fecha (Factura)
            $this->formatDate($ingreso->fecha_factura),
            
            // Columna 14: Importe de Estimación
            $this->formatNumber($ingreso->importe_estimacion),
            
            // Columna 15: I.V.A.
            $this->formatNumber($ingreso->iva),
            
            // Columna 16: Retenciones o sanciones
            $this->formatNumber($ingreso->retenciones_o_sanciones),
            
            // Columna 17: Total Estimacion con IVA
            $this->formatNumber($ingreso->total_estimacion_con_iva),
            
            // Columna 18: Fecha Elaboracion
            $this->formatDate($ingreso->fecha_elaboracion),
            
            // Columna 19: Estimación
            $this->formatNumber($ingreso->avance_obra_estimacion),
            
            // Columna 20: Real
            $this->formatNumber($ingreso->avance_obra_real),
            
            // Columna 21: % Avance Financiero
            $this->formatPercent($ingreso->porcentaje_avance_financiero),
            
            // Columna 22: 3.5 % Cargos Adicionales
            $this->formatNumber($ingreso->cargos_adicionales_3_5),
            
            // Columna 23: Retencion 5 al millar
            $this->formatNumber($ingreso->retencion_5_al_millar),
            
            // Columna 24: Sancion atrazo presntacion estimacion
            $this->formatNumber($ingreso->sancion_atrazo_presentacion_estimacion),
            
            // Columna 25: Sancion atraso de obra
            $this->formatNumber($ingreso->sancion_atraso_de_obra),
            
            // Columna 26: Sancion por obra mal ejecutada
            $this->formatNumber($ingreso->sancion_por_obra_mal_ejecutada),
            
            // Columna 27: Retencion por atraso en programa de obra
            $this->formatNumber($ingreso->retencion_por_atraso_en_programa_obra),
            
            // Columna 28: Amortización anticipo
            $this->formatNumber($ingreso->amortizacion_anticipo),
            
            // Columna 29: Amortización con I.V.A.
            $this->formatNumber($ingreso->amortizacion_con_iva),
            
            // Columna 30: Total deducciones
            $this->formatNumber($ingreso->total_deducciones),
            
            // Columna 31: Importe Facturado
            $this->formatNumber($ingreso->importe_facturado),
            
            // Columna 32: Liquido a cobrar
            $this->formatNumber($ingreso->liquido_a_cobrar),
            
            // Columna 33: Liquido Cobrado
            $this->formatNumber($ingreso->liquido_cobrado),
            
            // Columna 34: Fecha Cobro
            $this->formatDate($ingreso->fecha_cobro),
            
            // Columna 35: POR COBRAR
            $this->formatNumber($ingreso->por_cobrar),
            
            // Columna 36: POR FACTURAR
            $this->formatNumber($ingreso->por_facturar),
            
            // Columna 37: Por Estimar (omitido el segundo "Por Cobrar")
            $this->formatNumber($ingreso->por_estimar),
            
            // Columna 38: Status
            $ingreso->status ?? '',
            
            // Columna 39: Estimado menos Deducciones
            $this->formatNumber($ingreso->estimado_menos_deducciones),
            
            // Columna 40: Nº Cuenta
            $ingreso->no_cuenta ?? '',
            
            // Columna 41: Sucursal
            $ingreso->sucursal ?? '',
            
            // Columna 42: Clabe Interbancaria
            $ingreso->clabe_interbancaria ?? ''
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
            'A' => 30, 'B' => 20, 'C' => 18, 'D' => 18, 'E' => 22, 
            'F' => 22, 'G' => 30, 'H' => 22, 'I' => 15, 'J' => 12,
            'K' => 12, 'L' => 18, 'M' => 15, 'N' => 22, 'O' => 15,
            'P' => 22, 'Q' => 22, 'R' => 18, 'S' => 15, 'T' => 15,
            'U' => 18, 'V' => 22, 'W' => 22, 'X' => 30, 'Y' => 22,
            'Z' => 25, 'AA' => 22, 'AB' => 25, 'AC' => 22, 'AD' => 22,
            'AE' => 22, 'AF' => 22, 'AG' => 22, 'AH' => 22, 'AI' => 22,
            'AJ' => 22, 'AK' => 15, 'AL' => 15, 'AM' => 20, 'AN' => 15,
            'AO' => 15, 'AP' => 20
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Encabezados con estilo (42 columnas: A a AP)
        $sheet->getStyle('A1:AP1')->applyFromArray([
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
        $sheet->getStyle('A2:AP1000')->applyFromArray([
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
        $dateColumns = ['B', 'C', 'D', 'J', 'K', 'M', 'R', 'AG']; // Ajustadas según nuevas columnas
        foreach ($dateColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . '1000')
                  ->getNumberFormat()
                  ->setFormatCode('dd/mm/yyyy');
        }
        
        // Formato para columnas de moneda
        $currencyColumns = ['E', 'F', 'H', 'N', 'O', 'P', 'Q', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AH', 'AI', 'AJ']; // Ajustadas
        foreach ($currencyColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . '1000')
                  ->getNumberFormat()
                  ->setFormatCode('#,##0.00');
        }
        
        // Formato para porcentaje
        $sheet->getStyle('U2:U1000')
              ->getNumberFormat()
              ->setFormatCode('0.00%');
        
        // Alternar colores de filas para mejor lectura
        $sheet->getStyle('A2:AP1000')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2'],
            ],
        ]);
        
        // Fila impar diferente color
        $sheet->getStyle('A3:AP3')->applyFromArray([
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
                $event->sheet->setAutoFilter('A1:AP1');
                
                // Ajustar automáticamente el ancho de columnas basado en contenido
                foreach (range('A', 'AP') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(false);
                }
                
                // Ajustar algunas columnas específicas
                $event->sheet->getColumnDimension('A')->setWidth(30); // Cliente
                $event->sheet->getColumnDimension('G')->setWidth(30); // Convenio Apliacion (vacío)
                $event->sheet->getColumnDimension('X')->setWidth(30); // Sanciones largas
                
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