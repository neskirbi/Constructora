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
        // Construir consulta base
        $query = DB::table('destajos as d')
            ->select(
                'd.id',
                'd.consecutivo',
                'd.created_at',
                'd.referencia',
                'd.costo_operado',
                'd.iva',
                'd.total',
                'd.verificado',
                'c.refinterna as contrato_refinterna',
                'c.frente as contrato_frente',
                'c.contrato_no',
                'c.obra as contrato_obra',
                'p.clave as proveedor_clave',
                'p.nombre as proveedor_nombre',
                'p.clasificacion as proveedor_clasificacion'
            )
            ->leftJoin('contratos as c', 'd.id_contrato', '=', 'c.id')
            ->leftJoin('proveedores_servicios as p', 'd.id_proveedor', '=', 'p.id')
            ->whereBetween('d.created_at', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59'])
            ->orderBy('d.consecutivo', 'asc');

        // Si hay contrato específico, filtrar por él
        if ($this->contratoId && $this->contratoId !== 'todos') {
            $query->where('d.id_contrato', $this->contratoId);
        }

        $destajos = $query->get();
        
        // Array para almacenar todas las filas
        $filas = collect();
        
        foreach ($destajos as $destajo) {
            // Obtener detalles de este destajo
            $detalles = DB::table('destajodetalles')
                ->where('id_destajo', $destajo->id)
                ->get();
            
            if ($detalles->count() > 0) {
                $primerDetalle = true;
                foreach ($detalles as $detalle) {
                    $fila = new \stdClass();
                    $fila->consecutivo = $destajo->consecutivo;
                    $fila->fecha = $destajo->created_at;
                    $fila->obra = $destajo->contrato_obra; // NUEVA COLUMNA
                    $fila->no_obra = $destajo->contrato_refinterna;
                    $fila->frente = $destajo->contrato_frente;
                    $fila->clave_proveedor = $destajo->proveedor_clave;
                    $fila->proveedor = $destajo->proveedor_nombre;
                    $fila->clasificacion = $destajo->proveedor_clasificacion;
                    $fila->referencia = $destajo->referencia;
                    $fila->clave_producto = $detalle->clave;
                    $fila->descripcion = $detalle->descripcion;
                    $fila->unidad = $detalle->unidades;
                    $fila->cantidad = $detalle->cantidad;
                    $fila->precio_unitario = $detalle->ult_costo;
                    $fila->subtotal = $detalle->cantidad * $detalle->ult_costo;
                    
                    // Solo mostrar IVA y TOTAL en el primer detalle de cada destajo
                    if ($primerDetalle) {
                        $fila->iva_destajo = $destajo->iva;
                        $fila->total = $destajo->total;
                        $primerDetalle = false;
                    } else {
                        $fila->iva_destajo = '';
                        $fila->total = '';
                    }
                    
                    $filas->push($fila);
                }
            } else {
                // Destajo sin detalles
                $fila = new \stdClass();
                $fila->consecutivo = $destajo->consecutivo;
                $fila->fecha = $destajo->created_at;
                $fila->obra = $destajo->contrato_obra; // NUEVA COLUMNA
                $fila->no_obra = $destajo->contrato_refinterna;
                $fila->frente = $destajo->contrato_frente;
                $fila->clave_proveedor = $destajo->proveedor_clave;
                $fila->proveedor = $destajo->proveedor_nombre;
                $fila->clasificacion = $destajo->proveedor_clasificacion;
                $fila->referencia = $destajo->referencia;
                $fila->clave_producto = 'SIN DETALLES';
                $fila->descripcion = 'SIN DETALLES';
                $fila->unidad = '';
                $fila->cantidad = 0;
                $fila->precio_unitario = 0;
                $fila->subtotal = 0;
                $fila->iva_destajo = $destajo->iva;
                $fila->total = $destajo->total;
                
                $filas->push($fila);
            }
        }
        
        return $filas;
    }

    public function headings(): array
    {
        return [
            'CONSECUTIVO',
            'FECHA',
            'OBRA', // NUEVA COLUMNA
            'NO DE OBRA',
            'FRENTE',
            'CLAVE PROVEEDOR',
            'PROVEEDOR',
            'CLASIFICACIÓN',
            'REFERENCIA',
            'CLAVE PRODUCTO',
            'DESCRIPCIÓN',
            'UNIDAD',
            'CANTIDAD',
            'PRECIO UNITARIO',
            'SUBTOTAL',
            'IVA DEL DESTAJO',
            'TOTAL'
        ];
    }

    public function map($fila): array
    {
        return [
            $fila->consecutivo ?? '',
            $fila->fecha ? date('d/m/Y', strtotime($fila->fecha)) : '',
            $fila->obra ?? '', // NUEVA COLUMNA
            $fila->no_obra ?? '',
            $fila->frente ?? '',
            $fila->clave_proveedor ?? '',
            $fila->proveedor ?? '',
            $fila->clasificacion ?? '',
            $fila->referencia ?? '',
            $fila->clave_producto ?? '',
            $fila->descripcion ?? '',
            $fila->unidad ?? '',
            $fila->cantidad ?? 0,
            $fila->precio_unitario ?? 0,
            $fila->subtotal ?? 0,
            $fila->iva_destajo ?? '',
            $fila->total ?? '',
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
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,      // Fecha
            'M' => '#,##0.00',                              // Cantidad (ahora en columna M por el desplazamiento)
            'N' => '"$"#,##0.00',                           // Precio unitario
            'O' => '"$"#,##0.00',                           // Subtotal
            'P' => '"$"#,##0.00',                           // IVA del destajo
            'Q' => '"$"#,##0.00',                           // Total
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

                // Alineaciones específicas (ajustadas por la nueva columna)
                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center'); // Consecutivo
                $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setHorizontal('center'); // Fecha
                $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal('left');   // Obra (NUEVA)
                $sheet->getStyle('D2:I' . $lastRow)->getAlignment()->setHorizontal('left');   // Texto (desplazado)
                $sheet->getStyle('J2:L' . $lastRow)->getAlignment()->setHorizontal('left');   // Producto (desplazado)
                $sheet->getStyle('M2:Q' . $lastRow)->getAlignment()->setHorizontal('right');  // Números (desplazado)

                // Encabezado centrado
                $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension('1')->setRowHeight(25);

                // Zebra striping
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
                $sheet->setCellValue('B' . $filaInfo, $this->fechaInicio . ' - ' . $this->fechaFin);
                if ($this->contratoId && $this->contratoId !== 'todos') {
                    $contrato = DB::table('contratos')->where('id', $this->contratoId)->first();
                    $sheet->setCellValue('C' . $filaInfo, 'Contrato: ' . ($contrato->refinterna ?? $contrato->contrato_no ?? ''));
                } else {
                    $sheet->setCellValue('C' . $filaInfo, 'Contrato: TODOS');
                }
                $sheet->getStyle('A' . $filaInfo . ':C' . $filaInfo)->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                // Totales (ajustados por el desplazamiento de columnas)
                $filaTotales = $filaInfo + 2;
                $sheet->setCellValue('O' . $filaTotales, 'SUBTOTAL GENERAL:'); // Columna O
                $sheet->setCellValue('P' . $filaTotales, '=SUM(O2:O' . $lastRow . ')'); // Columna P
                $sheet->setCellValue('Q' . $filaTotales, 'TOTAL GENERAL:'); // Columna Q
                $sheet->setCellValue('Q' . ($filaTotales + 1), '=SUM(Q2:Q' . $lastRow . ')'); // Columna Q
                
                $sheet->getStyle('O' . $filaTotales . ':Q' . ($filaTotales + 1))->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFD700'],
                    ],
                ]);
                
                $sheet->getStyle('P' . $filaTotales)->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->getStyle('Q' . ($filaTotales + 1))->getNumberFormat()->setFormatCode('"$"#,##0.00');

                // Congelar paneles
                $sheet->freezePane('A2');
            },
        ];
    }
}