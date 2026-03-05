<?php

namespace App\Http\Controllers\Aingresos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\Models\Ingreso;
use App\Models\Contrato;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index(Request $request)
    {
        // Obtener parámetros de búsqueda
        $search = $request->input('search');
        
        // Construir consulta base SIN with()
        $query = Ingreso::query();
        
        // Aplicar búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Buscar en campos directos de ingresos (actualizados a los nuevos nombres)
                $q->where('no_estimacion', 'like', "%{$search}%")
                ->orWhere('factura', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                // Buscar en contratos usando join manual
                ->orWhereExists(function ($subquery) use ($search) {
                    $subquery->select(DB::raw(1))
                            ->from('contratos')
                            ->whereColumn('contratos.id', 'ingresos.id_contrato')
                            ->where(function ($q2) use ($search) {
                                $q2->where('contratos.contrato_no', 'like', "%{$search}%")
                                    ->orWhere('contratos.obra', 'like', "%{$search}%")
                                    ->orWhere('contratos.cliente', 'like', "%{$search}%");
                            });
                });
            });
        }
        
        // Obtener los ingresos con paginación
        $ingresos = $query->orderBy('created_at', 'desc')
                        ->paginate(15);
        
        return view('aingresos.ingresos.index', compact('ingresos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   function create()
{
    // Obtener contratos para el select
    $contratos = Contrato::orderBy('contrato_no', 'asc')->get();
    
    return view('aingresos.ingresos.create', compact('contratos'));
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function store(Request $request)
    {
        // Validación de campos
        $ingreso = new Ingreso(); // o el modelo que corresponda
        $ingreso->id = GetUuid();
        $ingreso->id_usuario = GetId();

        $ingreso->id_contrato = $request->id_contrato;
        $ingreso->no_estimacion = $request->no_estimacion;
        $ingreso->periodo_del = $request->periodo_del;
        $ingreso->periodo_al = $request->periodo_al;
        $ingreso->factura = $request->factura;
        $ingreso->fecha_factura = $request->fecha_factura;
        $ingreso->importe_estimacion = $request->importe_estimacion ?? 0;
        $ingreso->iva = $request->iva ?? 0;
        $ingreso->importe_iva = $request->importe_iva ?? 0;
        $ingreso->total_estimacion_con_iva = $request->total_estimacion_con_iva ?? 0;
        
        $ingreso->sicv_cop = $request->sicv_cop ?? 0;
        $ingreso->srcop_cdmx = $request->srcop_cdmx ?? 0;
        $ingreso->retencion_5_al_millar = $request->retencion_5_al_millar ?? 0;
        $ingreso->sancion_atrazo_presentacion_estimacion = $request->sancion_atrazo_presentacion_estimacion ?? 0;
        $ingreso->sancion_atraso_de_obra = $request->sancion_atraso_de_obra ?? 0;
        $ingreso->sancion_por_obra_mal_ejecutada = $request->sancion_por_obra_mal_ejecutada ?? 0;
        $ingreso->retencion_por_atraso_en_programa_obra = $request->retencion_por_atraso_en_programa_obra ?? 0;
        $ingreso->retenciones_o_sanciones = $request->retenciones_o_sanciones ?? 0;
        $ingreso->amortizacion_anticipo = $request->amortizacion_anticipo ?? 0;
        $ingreso->amortizacion_iva = $request->amortizacion_iva ?? 0;
        $ingreso->total_amortizacion = $request->total_amortizacion ?? 0;
        $ingreso->estimado_menos_deducciones = $request->estimado_menos_deducciones ?? 0;
        $ingreso->liquido_a_cobrar = $request->estimado_menos_deducciones ?? 0;
        $ingreso->status = $request->status ?? 'en_tramite';

        $ingreso->save();
        
        
        return redirect('ingresos')
            ->with('success', 'Ingreso creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
   function show($id)
    {
        $ingreso = Ingreso::findOrFail($id);
        
        // Obtener el contrato manualmente (sin relación)
        $contrato = Contrato::where('id', $ingreso->id_contrato)->first();
        
        // Obtener contratos para el select en caso de edición
        $contratos = Contrato::orderBy('contrato_no', 'asc')->get();
        
        return view('aingresos.ingresos.show', compact('ingreso', 'contrato', 'contratos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    /**
 * Update - Guarda Información Básica y Montos de Estimación (PRIMER FORM)
 */
public function update(Request $request, $id)
{
    try {
        $ingreso = Ingreso::findOrFail($id);
        
        // Validación de campos del primer formulario
        
        
        // Asignar manualmente cada campo para depurar
        $ingreso->no_estimacion = $request->no_estimacion;
        $ingreso->periodo_del = $request->periodo_del;
        $ingreso->periodo_al = $request->periodo_al;
        $ingreso->importe_estimacion = $request->importe_estimacion ?? 0;
        $ingreso->iva = $request->iva ?? 0;
        $ingreso->importe_iva = $request->importe_iva ?? 0;
        
        $ingreso->total_estimacion_con_iva = $request->total_estimacion_con_iva ?? 0;        
        

        $ingreso->sicv_cop = $request->sicv_cop ?? 0;
        $ingreso->srcop_cdmx = $request->srcop_cdmx ?? 0;
        $ingreso->retencion_5_al_millar = $request->retencion_5_al_millar ?? 0;
        $ingreso->sancion_atrazo_presentacion_estimacion = $request->sancion_atrazo_presentacion_estimacion ?? 0;
        $ingreso->sancion_atraso_de_obra = $request->sancion_atraso_de_obra ?? 0;
        $ingreso->sancion_por_obra_mal_ejecutada = $request->sancion_por_obra_mal_ejecutada ?? 0;
        $ingreso->retencion_por_atraso_en_programa_obra = $request->retencion_por_atraso_en_programa_obra ?? 0;
        $ingreso->retenciones_o_sanciones = $request->retenciones_o_sanciones ?? 0;
        $ingreso->amortizacion_anticipo = $request->amortizacion_anticipo ?? 0;
        $ingreso->amortizacion_iva = $request->amortizacion_iva ?? 0;
        $ingreso->total_amortizacion = $request->total_amortizacion ?? 0;
        $ingreso->estimado_menos_deducciones = $request->estimado_menos_deducciones ?? 0;
        $ingreso->liquido_a_cobrar = $request->estimado_menos_deducciones ?? 0;
        
        // Guardar
        $ingreso->save();
        
        return redirect()->route('ingresos.show', $ingreso->id)
            ->with('success', 'Información actualizada exitosamente.');
            
    } catch (\Exception $e) {
        // Si hay error, lo mostramos
        return back()->withErrors(['error' => 'Error: ' . $e->getMessage()])->withInput();
    }
}

/**
 * updateFacturacion - Guarda solo datos de facturación (SEGUNDO FORM)
 */
public function updateFacturacion(Request $request, $id)
{
    // Buscar el ingreso
    $ingreso = Ingreso::findOrFail($id);
    
    // Validación de campos de facturación Y COBROS
    $validated = $request->validate([
        // Facturación
        'factura' => 'nullable|string|max:255',
        'fecha_factura' => 'nullable|date',
        'status' => 'nullable|string|in:pagado,en_tramite',
        
        // Cobros
        'liquido_cobrado' => 'nullable|numeric|min:0',
        'fecha_cobro' => 'nullable|date',
        'por_cobrar' => 'nullable|numeric',
    ]);
    
    // Recalcular por_cobrar si viene líquido_cobrado
    if (isset($validated['liquido_cobrado'])) {
        $liquidoACobrar = $ingreso->estimado_menos_deducciones ?? 0;
        $validated['por_cobrar'] = $liquidoACobrar - $validated['liquido_cobrado'];
    }
    
    // Actualizar todos los campos (facturación + cobros)
    $ingreso->update($validated);
    
    return redirect()->route('ingresos.show', $ingreso->id)
        ->with('success', 'Datos guardados exitosamente.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
   function destroy($id)
    {
        // Buscar el ingreso
        $ingreso = Ingreso::findOrFail($id);
        
        // Solo eliminar si está verificado (verificado = 1)
        if ($ingreso->verificado != 1) {
            return redirect()->route('ingresos')
                ->with('error', 'No se puede eliminar el ingreso porque ya está verificado.');
        }
        
        $ingreso->delete();
        
        return redirect()->route('ingresos')
            ->with('success', 'Ingreso eliminado exitosamente.');
    }
    
    /**
     * Obtener datos del contrato para AJAX.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    function getContratoData($id)
    {
        $contrato = Contrato::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'contrato_no' => $contrato->contrato_no,
                'obra' => $contrato->obra,
                'cliente' => $contrato->cliente,
                'empresa' => $contrato->empresa,
                'total_contrato' => $contrato->total_contrato,
                'anticipo' => $contrato->anticipo,
            ]
        ]);
    }


    /**
 * Obtener el último ingreso de un contrato para AJAX.
 *
 * @param  string  $id
 * @return \Illuminate\Http\Response
 */
public function getUltimoIngreso($id)
{
    $ultimoIngreso = Ingreso::where('id_contrato', $id)
                            ->orderBy('created_at', 'desc')
                            ->first();
    
    if ($ultimoIngreso) {
        return response()->json([
            'success' => true,
            'data' => $ultimoIngreso
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'No hay ingresos previos para este contrato'
    ]);
}

    
}