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
        
        // Construir la consulta con JOIN usando los nombres correctos de los campos
        $query = DB::table('ingresos')
            ->join('contratos', 'ingresos.id_contrato', '=', 'contratos.id')
            ->select(
                // Campos de contrato (según estructura real)
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
                
                // Campos de ingresos (según estructura real)
                'ingresos.no_estimacion',
                'ingresos.periodo_del',
                'ingresos.periodo_al',
                'ingresos.factura',
                'ingresos.fecha_factura',
                'ingresos.importe_estimacion',
                'ingresos.iva',
                'ingresos.retenciones_o_sanciones',
                'ingresos.total_estimacion_con_iva',
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
                'ingresos.por_estimar',
                'ingresos.status',
                'ingresos.estimado_menos_deducciones',
                'ingresos.verificado',
                'ingresos.created_at'
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
            'importe_estimacion' => $ingresos->sum('importe_estimacion'),
            'iva' => $ingresos->sum('iva'),
            'total_con_iva' => $ingresos->sum('total_estimacion_con_iva'),
            'liquido_cobrar' => $ingresos->sum('liquido_a_cobrar'),
            'importe_facturado' => $ingresos->sum('importe_facturado'),
            'liquido_cobrado' => $ingresos->sum('liquido_cobrado'),
            'total_deducciones' => $ingresos->sum('total_deducciones'),
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
        
        // Si es una petición AJAX, devolver solo el contenido del reporte
        if ($request->ajax()) {
            $view = view('reportes.partials.resultado_tabla', compact(
                'ingresos',
                'fechaDesde',
                'fechaHasta',
                'idContrato',
                'totales',
                'contratoSeleccionado'
            ))->render();
            
            return response()->json([
                'html' => $view,
                'totales' => $totales,
                'fechaDesde' => $fechaDesde,
                'fechaHasta' => $fechaHasta,
                'contratoSeleccionado' => $contratoSeleccionado
            ]);
        }
        
        // Si no es AJAX, devolver la vista completa (para compatibilidad)
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
        
        // Verificar si existe la clase de exportación de Excel
        if (class_exists('\Maatwebsite\Excel\Facades\Excel') && class_exists('\App\Exports\IngresosExport')) {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\IngresosExport($fechaDesde, $fechaHasta, $idContrato),
                $nombreArchivo
            );
        } else {
            // Si no existe Laravel Excel, generar un CSV simple
            return $this->exportarCSV($fechaDesde, $fechaHasta, $idContrato);
        }
    }
    
    private function exportarCSV($fechaDesde, $fechaHasta, $idContrato)
    {
        // Obtener los datos usando la misma consulta que en generar()
        $query = DB::table('ingresos')
            ->join('contratos', 'ingresos.id_contrato', '=', 'contratos.id')
            ->select(
                'contratos.contrato_no',
                'contratos.obra',
                'contratos.cliente',
                'ingresos.no_estimacion',
                'ingresos.periodo_del',
                'ingresos.periodo_al',
                'ingresos.factura',
                'ingresos.fecha_factura',
                'ingresos.importe_estimacion',
                'ingresos.iva',
                'ingresos.total_estimacion_con_iva',
                'ingresos.importe_facturado',
                'ingresos.liquido_a_cobrar',
                'ingresos.liquido_cobrado',
                'ingresos.fecha_cobro',
                'ingresos.status',
                'ingresos.verificado',
                'ingresos.created_at'
            )
            ->whereBetween('ingresos.created_at', [$fechaDesde, $fechaHasta])
            ->orderBy('ingresos.created_at');
        
        if ($idContrato && $idContrato != 'todos') {
            $query->where('ingresos.id_contrato', $idContrato);
        }
        
        $ingresos = $query->get();
        
        $filename = 'reporte_ingresos_' . date('Ymd_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($ingresos) {
            $file = fopen('php://output', 'w');
            
            // Encabezados
            fputcsv($file, [
                'Contrato',
                'Obra',
                'Cliente',
                'Estimación',
                'Periodo Del',
                'Periodo Al',
                'Factura',
                'Fecha Factura',
                'Importe Estimación',
                'IVA',
                'Total con IVA',
                'Importe Facturado',
                'Líquido a Cobrar',
                'Líquido Cobrado',
                'Fecha Cobro',
                'Status',
                'Verificado',
                'Fecha Creación'
            ]);
            
            // Datos
            foreach ($ingresos as $ingreso) {
                fputcsv($file, [
                    $ingreso->contrato_no,
                    $ingreso->obra,
                    $ingreso->cliente,
                    $ingreso->no_estimacion,
                    $ingreso->periodo_del,
                    $ingreso->periodo_al,
                    $ingreso->factura,
                    $ingreso->fecha_factura,
                    $ingreso->importe_estimacion,
                    $ingreso->iva,
                    $ingreso->total_estimacion_con_iva,
                    $ingreso->importe_facturado,
                    $ingreso->liquido_a_cobrar,
                    $ingreso->liquido_cobrado,
                    $ingreso->fecha_cobro,
                    $ingreso->status,
                    $ingreso->verificado == 1 ? 'Pendiente' : ($ingreso->verificado == 2 ? 'Verificado' : 'Rechazado'),
                    $ingreso->created_at
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}