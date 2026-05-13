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
                'd.id',
                'd.consecutivo',
                'd.created_at as fecha',
                'd.referencia',
                'c.consecutivo as almacen',
                'c.frente',
                'p.clave as clave_proveedor',
                'p.nombre as proveedor'
            )
            ->leftJoin('contratos as c', 'd.id_contrato', '=', 'c.id')
            ->leftJoin('proveedores_servicios as p', 'd.id_proveedor', '=', 'p.id')
            ->whereBetween('d.created_at', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59'])
            ->orderBy('d.consecutivo', 'asc');

        if ($this->contratoId && $this->contratoId !== 'todos') {
            $query->where('d.id_contrato', $this->contratoId);
        }

        $destajos = $query->get();
        $filas = collect();

        foreach ($destajos as $destajo) {
            $detalles = DB::table('destajodetalles')
                ->where('id_destajo', $destajo->id)
                ->get();

            if ($detalles->count() > 0) {
                foreach ($detalles as $detalle) {
                    $fila = new \stdClass();
                    $fila->consecutivo = $destajo->consecutivo;
                    $fila->clave = $detalle->clave;
                    $fila->descripcion = $detalle->descripcion;
                    $fila->clave_por = $destajo->clave_proveedor;
                    $fila->fecha = $destajo->fecha;
                    $fila->almacen = $destajo->almacen;
                    $fila->costo_unitario = $detalle->ult_costo;
                    $fila->cantidad = $detalle->cantidad;
                    $fila->costo_operado = $detalle->cantidad * $detalle->ult_costo;
                    $fila->unidad = $detalle->unidades;
                    $fila->referencia = $destajo->referencia;
                    $fila->nombre_proveedor = $destajo->proveedor;
                    $fila->frente = $destajo->frente;
                    
                    $filas->push($fila);
                }
            } else {
                $fila = new \stdClass();
                $fila->consecutivo = $destajo->consecutivo;
                $fila->clave = 'SIN DATOS';
                $fila->descripcion = 'SIN DATOS';
                $fila->clave_por = $destajo->clave_proveedor;
                $fila->fecha = $destajo->fecha;
                $fila->almacen = $destajo->almacen;
                $fila->costo_unitario = 0;
                $fila->cantidad = 0;
                $fila->costo_operado = 0;
                $fila->unidad = '';
                $fila->referencia = $destajo->referencia;
                $fila->nombre_proveedor = $destajo->proveedor;
                $fila->frente = $destajo->frente;
                
                $filas->push($fila);
            }
        }
        
        return $filas;
    }

    public function headings(): array
    {
        return [
            'Consecutivo',
            'Clave',
            'Descripción',
            'Clave por',
            'Fecha',
            'Almacen',
            'Costo Unitario',
            'Cantidad',
            'Costo operado',
            'Unidad',
            'REFERENCIA',
            'Nombre Proveedor',
            'Frente'
        ];
    }

    public function map($fila): array
    {
        return [
            $fila->consecutivo ?? '',
            $fila->clave ?? '',
            $fila->descripcion ?? '',
            $fila->clave_por ?? '',
            $fila->fecha ? date('d/m/Y', strtotime($fila->fecha)) : '',
            $fila->almacen ?? '',
            $fila->costo_unitario ?? 0,
            $fila->cantidad ?? 0,
            $fila->costo_operado ?? 0,
            $fila->unidad ?? '',
            $fila->referencia ?? '',
            $fila->nombre_proveedor ?? '',
            $fila->frente ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,   // Fecha
            'H' => '#,##0.00',                           // Cantidad
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
                    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);

                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
                    'font' => ['size' => 11],
                ]);

                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('E2:E' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('G2:I' . $lastRow)->getAlignment()->setHorizontal('right');

                $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension('1')->setRowHeight(25);

                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                        ]);
                    }
                }

                $sheet->freezePane('A2');
            },
        ];
    }
}