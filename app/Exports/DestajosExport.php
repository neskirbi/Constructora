<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DestajosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
                'd.consecutivo',
                'd.created_at',
                'd.clave_concepto',
                'd.descripcion_concepto',
                'd.unidad_concepto',
                'd.costo_unitario_concepto',
                'd.cantidad',
                'd.referencia',
                'd.costo_operado',
                'd.iva',
                'd.total',
                'c.refinterna',
                'c.frente',
                'p.clave as clave_proveedor',
                'p.nombre as nombre_proveedor',
                'p.clasificacion'
            )
            ->leftJoin('contratos as c', 'd.id_contrato', '=', 'c.id')
            ->leftJoin('proveedores_servicios as p', 'd.id_proveedor', '=', 'p.id')
            ->whereBetween('d.created_at', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59'])
            ->orderBy('d.created_at', 'asc');

        if ($this->contratoId) {
            $query->where('d.id_contrato', $this->contratoId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Consecutivo',
            'Fecha',
            'No de Obra',
            'Frente',
            'Clave proveedor',
            'Tipo de proveedor',
            'Nombre Proveedor',
            'Clave de concepto',
            'Descripción del concepto',
            'Unidad del concepto',
            'Costo Unitario del concepto',
            'Cantidad',
            'Referencia',
            'Costo operado',
            'IVA',
            'Total'
        ];
    }

    public function map($destajo): array
    {
        return [
            $destajo->consecutivo ?? '',
            $destajo->created_at ? date('Y-m-d', strtotime($destajo->created_at)) : '',
            $destajo->refinterna ?? '',
            $destajo->frente ?? '',
            $destajo->clave_proveedor ?? '',
            'Destajo', // Valor fijo para tipo de proveedor
            $destajo->nombre_proveedor ?? '',
            $destajo->clave_concepto ?? '',
            $destajo->descripcion_concepto ?? '',
            $destajo->unidad_concepto ?? '',
            $destajo->costo_unitario_concepto ?? 0,
            $destajo->cantidad ?? 0,
            $destajo->referencia ?? '',
            $destajo->costo_operado ?? 0,
            $destajo->iva ?? 0,
            $destajo->total ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}