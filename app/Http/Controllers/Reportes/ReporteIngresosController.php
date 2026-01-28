<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReporteIngresosController extends Controller
{
    public function index()
    {
        // Obtener lista de contratos para el select
        $contratos = DB::table('contratos')
            ->select('id', 'contrato_no', 'obra', 'cliente')
            ->orderBy('contrato_no')
            ->get();
        
        return view('reportes.ingresos', compact('contratos'));
    }
    
    public function generar(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
            'id_contrato' => 'nullable'
        ]);
        
        $fechaDesde = $request->fecha_desde;
        $fechaHasta = $request->fecha_hasta;
        $idContrato = $request->id_contrato;
        
        // Construir la consulta con JOIN incluyendo TODOS los campos
        $query = DB::table('ingresos')
            ->join('contratos', 'ingresos.id_contrato', '=', 'contratos.id')
            ->select(
                // Campos de contrato
                'contratos.obra',
                'contratos.empresa',
                'contratos.contrato_no',
                'contratos.cliente',
                'contratos.contrato_fecha',
                'contratos.inicio_de_obra',
                'contratos.terminacion_de_obra',
                'contratos.anticipo',
                'contratos.importe_contrato',
                'contratos.importe_convenio',
                'contratos.total_convenio',
                'contratos.total_total',
                'contratos.n_cuenta',
                'contratos.sucursal',
                'contratos.clabe_interbancaria',
                
                // Campos de ingresos
                'ingresos.area',
                'ingresos.estimacion',
                'ingresos.periodo_del',
                'ingresos.periodo_al',
                'ingresos.factura',
                'ingresos.fecha_factura',
                'ingresos.importe_de_estimacion',
                'ingresos.iva',
                'ingresos.retenciones_o_sanciones',
                'ingresos.total_estimacion_con_iva',
                'ingresos.fecha_elaboracion',
                'ingresos.avance_obra_estimacion',
                'ingresos.avance_obra_real',
                'ingresos.porcentaje_avance_financiero',
                'ingresos.cargos_adicionales_35_porciento',
                'ingresos.retencion_5_al_millar',
                'ingresos.sancion_atraso_presentacion_estimacion',
                'ingresos.sancion_atraso_de_obra',
                'ingresos.sancion_por_obra_mal_ejecutada',
                'ingresos.retencion_por_atraso_en_programa_de_obra',
                'ingresos.amortizacion_anticipo',
                'ingresos.amortizacion_con_iva',
                'ingresos.total_deducciones',
                'ingresos.importe_facturado',
                'ingresos.liquido_a_cobrar',
                'ingresos.liquido_cobrado',
                'ingresos.fecha_cobro',
                'ingresos.por_cobrar',
                'ingresos.por_facturar',
                'ingresos.status',
                'ingresos.estimado_menos_deducciones',
                'ingresos.verificado'
            )
            ->whereBetween('ingresos.created_at', [$fechaDesde, $fechaHasta])
            ->orderBy('ingresos.created_at');
        
        // Filtrar por contrato específico si se seleccionó
        if ($idContrato && $idContrato != 'todos') {
            $query->where('ingresos.id_contrato', $idContrato);
        }
        
        $ingresos = $query->get();
        
        // Calcular totales básicos para la vista web
        $totales = [
            'importe_estimacion' => $ingresos->sum('importe_de_estimacion'),
            'iva' => $ingresos->sum('iva'),
            'total_con_iva' => $ingresos->sum('total_estimacion_con_iva'),
            'liquido_cobrar' => $ingresos->sum('liquido_a_cobrar'),
            'count' => $ingresos->count()
        ];
        
        // Obtener nombre del contrato si se filtró
        $contratoSeleccionado = null;
        if ($idContrato && $idContrato != 'todos') {
            $contratoSeleccionado = DB::table('contratos')
                ->select('contrato_no', 'obra', 'cliente')
                ->where('id', $idContrato)
                ->first();
        }
        
        return view('reportes.resultado', compact(
            'ingresos',
            'fechaDesde',
            'fechaHasta',
            'idContrato',
            'totales',
            'contratoSeleccionado'
        ));
    }
    
    public function exportarExcel(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
            'id_contrato' => 'nullable'
        ]);
        
        $fechaDesde = $request->fecha_desde;
        $fechaHasta = $request->fecha_hasta;
        $idContrato = $request->id_contrato;
        
        // Obtener nombre del contrato si se filtró
        $nombreContrato = '';
        if ($idContrato && $idContrato != 'todos') {
            $contrato = DB::table('contratos')
                ->select('contrato_no', 'obra')
                ->where('id', $idContrato)
                ->first();
            $nombreContrato = $contrato ? "_{$contrato->contrato_no}" : '';
        }
        
        $nombreArchivo = 'reporte_ingresos' . $nombreContrato . '_' . date('Ymd_His') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\IngresosExport($fechaDesde, $fechaHasta, $idContrato),
            $nombreArchivo
        );
    }
    
    private function generateCsvData($ingresos, $fechaDesde, $fechaHasta)
    {
        $output = fopen('php://output', 'w');
        ob_start();
        
        // Encabezado del reporte
        fputcsv($output, ['REPORTE COMPLETO DE INGRESOS'], ';');
        fputcsv($output, ["Periodo: {$fechaDesde} al {$fechaHasta}"], ';');
        fputcsv($output, ["Total registros: " . $ingresos->count()], ';');
        fputcsv($output, ['']); // Línea en blanco
        
        // Encabezados de columnas EN EL ORDEN DE TU LISTA
        fputcsv($output, [
            'N° obra',
            'Empresa',
            'Numero de Contrato',
            'Descripcion según contrato',
            'Cliente',
            'AREA',
            'Fecha Firma de Contrato',
            'Fecha Inicio de Obra',
            'Fecha Terminación de Obra',
            'Importe de Anticipo c/IVA',
            'Importe de Contrato c/IVA',
            'Convenio Aplicacion de monto c/IVA',
            'Total a cobrar contrato c/IVA',
            '# Estimación',
            'del',
            'al',
            'Factura',
            'Fecha',
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
            'Status',
            'Estimado menos Deducciones',
            'Nº Cuenta',
            'Sucursal',
            'Clabe Interbancaria',
            'Estado Verificación'
        ], ';');
        
        // Datos
        foreach ($ingresos as $ingreso) {
            // Convertir estado de verificado
            $estadoVerificado = $ingreso->verificado == '2' ? 'Verificado' : 
                               ($ingreso->verificado == '0' ? 'Rechazado' : 'Pendiente');
            
            fputcsv($output, [
                // Campos de contrato
                $ingreso->obra ?? '',
                $ingreso->empresa ?? '',
                $ingreso->contrato_no ?? '',
                $ingreso->obra ?? '', // Descripcion según contrato (misma que obra)
                $ingreso->cliente ?? '',
                $ingreso->area ?? '',
                $ingreso->contrato_fecha ?? '',
                $ingreso->inicio_de_obra ?? '',
                $ingreso->terminacion_de_obra ?? '',
                number_format($ingreso->anticipo ?? 0, 2),
                number_format($ingreso->importe_contrato ?? 0, 2),
                number_format($ingreso->total_convenio ?? 0, 2),
                number_format($ingreso->total_total ?? 0, 2),
                
                // Campos de ingresos
                $ingreso->estimacion ?? '',
                $ingreso->periodo_del ?? '',
                $ingreso->periodo_al ?? '',
                $ingreso->factura ?? '',
                $ingreso->fecha_factura ?? '',
                number_format($ingreso->importe_de_estimacion ?? 0, 2),
                number_format($ingreso->iva ?? 0, 2),
                number_format($ingreso->retenciones_o_sanciones ?? 0, 2),
                number_format($ingreso->total_estimacion_con_iva ?? 0, 2),
                $ingreso->fecha_elaboracion ?? '',
                number_format($ingreso->avance_obra_estimacion ?? 0, 2),
                number_format($ingreso->avance_obra_real ?? 0, 2),
                number_format($ingreso->porcentaje_avance_financiero ?? 0, 2),
                number_format($ingreso->cargos_adicionales_35_porciento ?? 0, 2),
                number_format($ingreso->retencion_5_al_millar ?? 0, 2),
                number_format($ingreso->sancion_atraso_presentacion_estimacion ?? 0, 2),
                number_format($ingreso->sancion_atraso_de_obra ?? 0, 2),
                number_format($ingreso->sancion_por_obra_mal_ejecutada ?? 0, 2),
                number_format($ingreso->retencion_por_atraso_en_programa_de_obra ?? 0, 2),
                number_format($ingreso->amortizacion_anticipo ?? 0, 2),
                number_format($ingreso->amortizacion_con_iva ?? 0, 2),
                number_format($ingreso->total_deducciones ?? 0, 2),
                number_format($ingreso->importe_facturado ?? 0, 2),
                number_format($ingreso->liquido_a_cobrar ?? 0, 2),
                number_format($ingreso->liquido_cobrado ?? 0, 2),
                $ingreso->fecha_cobro ?? '',
                number_format($ingreso->por_cobrar ?? 0, 2),
                number_format($ingreso->por_facturar ?? 0, 2),
                $ingreso->status ?? '',
                number_format($ingreso->estimado_menos_deducciones ?? 0, 2),
                
                // Campos adicionales de contrato
                $ingreso->n_cuenta ?? '',
                $ingreso->sucursal ?? '',
                $ingreso->clabe_interbancaria ?? '',
                
                // Estado de verificación
                $estadoVerificado
            ], ';');
        }
        
        fclose($output);
        return ob_get_clean();
    }
}