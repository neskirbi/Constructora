<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

class ComprasExport implements 
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
    protected $claveProveedor;

    public function __construct($fechaInicio, $fechaFin, $contratoId = null, $claveProveedor = null)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->contratoId = $contratoId;
        $this->claveProveedor = $claveProveedor;
    }

    public function collection()
    {
        $query = DB::table('compras as c')
            ->select(
                'c.id',
                'c.numeracion',
                'c.consecutivo',
                'ct.consecutivo as cons',
                'c.created_at',
                'c.referencia',
                'c.costo_operado',
                DB::raw("
                    CASE 
                        WHEN c.iva > 20 THEN c.iva 
                        ELSE (c.costo_operado * c.iva / 100) 
                    END as iva
                "),
                'c.total',
                'c.verificado',
                'c.metodo_pago',
                'c.empresa_pago',
                'c.factura',
                'ct.frente as contrato_frente',
                'ct.contrato_no',
                'ct.obra as contrato_obra',
                'p.clave as proveedor_clave',
                'p.nombre as proveedor_nombre',
                'p.clasificacion as proveedor_clasificacion',
                DB::raw("CONCAT(a.nombres, ' ', a.apellidos) as comprador_nombre")
            )
            ->leftJoin('contratos as ct', 'c.id_contrato', '=', 'ct.id')
            ->leftJoin('proveedores_servicios as p', 'c.id_proveedor', '=', 'p.id')
            ->leftJoin('acompras as a', 'c.id_usuario', '=', 'a.id')
            ->whereBetween('c.created_at', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59'])
            ->orderBy('c.created_at', 'asc');

        if ($this->contratoId && $this->contratoId !== 'todos') {
            $query->where('c.id_contrato', $this->contratoId);
        }

        if ($this->claveProveedor) {
            $query->where('p.clave', 'LIKE', '%' . $this->claveProveedor . '%');
        }

        $compras = $query->get();
        $filas = collect();
        
        foreach ($compras as $compra) {
            $detalles = DB::table('compradetalle')
                ->where('id_compra', $compra->id)
                ->get();
            
            if ($detalles->count() > 0) {
                foreach ($detalles as $detalle) {
                    $fila = new \stdClass();
                    $fila->consecutivo = $compra->numeracion;
                    $fila->fecha = $this->getDateOnly($compra->created_at);
                    $fila->requisicion = $compra->consecutivo;
                    $fila->no_obra = $compra->cons;
                    $fila->frente = $compra->contrato_frente;
                    $fila->clave_proveedor = $compra->proveedor_clave;
                    $fila->proveedor = $compra->proveedor_nombre;
                    $fila->clave_producto = $detalle->clave;
                    $fila->descripcion = Str::limit($detalle->descripcion, 30, '...');
                    $fila->unidad = $detalle->unidades;
                    $fila->cantidad = (float) $detalle->cantidad;
                    $fila->precio_unitario = (float) $detalle->ult_costo;
                    $fila->subtotal = (float) ($detalle->cantidad * $detalle->ult_costo);
                    $fila->iva_compra = (float) $compra->iva;
                    $fila->total = (float) $compra->total;
                    $fila->observaciones = $compra->referencia;
                    $fila->tipo_pago = $compra->metodo_pago;
                    $fila->empresa_pagadora = $compra->empresa_pago;
                    $fila->comprador = $compra->comprador_nombre ?? 'Desconocido';
                    $fila->entrega = $detalle->tipo_entrega ?? null;
                    $fila->fecha_entrega = $detalle->fecha_entrega ?? null;
                    $fila->obs_entrega = $detalle->comentarios ?? null;
                    $fila->factura = $compra->factura;
                    
                    $filas->push($fila);
                }
            } else {
                $fila = new \stdClass();
                $fila->consecutivo = $compra->numeracion;
                $fila->fecha = $this->getDateOnly($compra->created_at);
                $fila->requisicion = $compra->consecutivo;
                $fila->no_obra = $compra->cons;
                $fila->frente = $compra->contrato_frente;
                $fila->clave_proveedor = $compra->proveedor_clave;
                $fila->proveedor = $compra->proveedor_nombre;
                $fila->clave_producto = 'SIN DETALLES';
                $fila->descripcion = 'SIN DETALLES';
                $fila->unidad = '';
                $fila->cantidad = 0;
                $fila->precio_unitario = 0;
                $fila->subtotal = 0;
                $fila->iva_compra = (float) $compra->iva;
                $fila->total = (float) $compra->total;
                $fila->observaciones = $compra->referencia;
                $fila->tipo_pago = $compra->metodo_pago;
                $fila->empresa_pagadora = $compra->empresa_pago;
                $fila->comprador = $compra->comprador_nombre ?? 'Desconocido';
                $fila->entrega = null;
                $fila->fecha_entrega = null;
                $fila->obs_entrega = null;
                $fila->factura = $compra->factura;
                
                $filas->push($fila);
            }
        }
        
        return $filas;
    }

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

    public function headings(): array
    {
        return [
            'CONSECUTIVO',
            'FECHA DE TRÁMITE',
            'REQUISICIÓN',
            'NO DE OBRA',
            'FRENTE',
            'CLAVE PROVEDOR',
            'PROVEEDOR',
            'CLAVE PRODUCTO',
            'DESCRIPCION',
            'UNIDAD',
            'CANTIDAD',
            'PRECIO UNITARIO',
            'SUBTOTAL',
            'IVA DE LA COMPRA',
            'TOTAL',
            'OBSERVACIONES',
            'TIPO DE PAGO',
            'EMPRESA PAGADORA',
            'COMPRADOR',
            'ENTREGA',
            'FECHA DE ENTREGA',
            'OBSERVACIONES DE ENTREGA',
            'FACTURA'
        ];
    }

    public function map($fila): array
    {
        return [
            $fila->consecutivo ?? '',
            $fila->fecha ?? '',
            $fila->requisicion ?? '',
            $fila->no_obra ?? '',
            $fila->frente ?? '',
            $fila->clave_proveedor ?? '',
            $fila->proveedor ?? '',
            $fila->clave_producto ?? '',
            $fila->descripcion ?? '',
            $fila->unidad ?? '',
            $fila->cantidad ?? 0,
            $fila->precio_unitario ?? 0,
            $fila->subtotal ?? 0,
            $fila->iva_compra ?? 0,
            $fila->total ?? 0,
            $fila->observaciones ?? '',
            $fila->tipo_pago ?? '',
            $fila->empresa_pagadora ?? '',
            $fila->comprador ?? '',
            $fila->entrega ?? '',
            $fila->fecha_entrega ?? '',
            $fila->obs_entrega ?? '',
            $fila->factura ?? '',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => 'dd/mm/yyyy',
            'K' => '#,##0.00',
            'L' => '#,##0.00',
            'M' => '#,##0.00',
            'N' => '#,##0.00',
            'O' => '#,##0.00',
            'U' => 'dd/mm/yyyy',
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => ['size' => 11],
                ]);

                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('C2:I' . $lastRow)->getAlignment()->setHorizontal('left');
                $sheet->getStyle('J2:J' . $lastRow)->getAlignment()->setHorizontal('left');
                $sheet->getStyle('K2:O' . $lastRow)->getAlignment()->setHorizontal('right');
                $sheet->getStyle('P2:P' . $lastRow)->getAlignment()->setHorizontal('left');
                $sheet->getStyle('Q2:Q' . $lastRow)->getAlignment()->setHorizontal('left');
                $sheet->getStyle('R2:R' . $lastRow)->getAlignment()->setHorizontal('left');
                $sheet->getStyle('S2:S' . $lastRow)->getAlignment()->setHorizontal('left');
                $sheet->getStyle('T2:T' . $lastRow)->getAlignment()->setHorizontal('left');
                $sheet->getStyle('U2:U' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('V2:V' . $lastRow)->getAlignment()->setHorizontal('left');
                $sheet->getStyle('W2:W' . $lastRow)->getAlignment()->setHorizontal('left');

                $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension('1')->setRowHeight(25);

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

                $filaInfo = $lastRow + 2;
                $sheet->setCellValue('A' . $filaInfo, 'Filtro aplicado:');
                $sheet->setCellValue('B' . $filaInfo, $this->fechaInicio . ' - ' . $this->fechaFin);
                
                $columnaActual = 'C';
                if ($this->contratoId && $this->contratoId !== 'todos') {
                    $contrato = DB::table('contratos')->where('id', $this->contratoId)->first();
                    $sheet->setCellValue($columnaActual . $filaInfo, 'Contrato: ' . ($contrato->refinterna ?? $contrato->contrato_no ?? ''));
                    $columnaActual = chr(ord($columnaActual) + 1);
                } else {
                    $sheet->setCellValue($columnaActual . $filaInfo, 'Contrato: TODOS');
                    $columnaActual = chr(ord($columnaActual) + 1);
                }
                
                if ($this->claveProveedor) {
                    $sheet->setCellValue($columnaActual . $filaInfo, 'Clave Proveedor: ' . $this->claveProveedor);
                }

                $filaTotales = $filaInfo + 2;
                $sheet->setCellValue('M' . $filaTotales, 'SUBTOTAL GENERAL:');
                $sheet->setCellValue('N' . $filaTotales, '=SUM(M2:M' . $lastRow . ')');
                $sheet->setCellValue('O' . $filaTotales, 'TOTAL GENERAL:');
                $sheet->setCellValue('O' . ($filaTotales + 1), '=SUM(O2:O' . $lastRow . ')');
                
                $sheet->getStyle('M' . $filaTotales . ':O' . ($filaTotales + 1))->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFD700'],
                    ],
                ]);
                
                $sheet->getStyle('N' . $filaTotales)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('O' . ($filaTotales + 1))->getNumberFormat()->setFormatCode('#,##0.00');

                $sheet->freezePane('A2');
            },
        ];
    }
}