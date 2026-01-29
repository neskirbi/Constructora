<?php

namespace App\Http\Controllers\Administradores;

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
        
        return view('administradores.ingresos.index', compact('ingresos'));
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
        
        return view('administradores.ingresos.create', compact('contratos'));
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
        $validated = $request->validate([
            'id_contrato' => 'required|exists:contratos,id',
            'area' => 'nullable|string|max:255',
            'estimacion' => 'required|string|max:255',
            'periodo_del' => 'nullable|date',
            'periodo_al' => 'nullable|date|after_or_equal:periodo_del',
            'factura' => 'nullable|string|max:255',
            'fecha_factura' => 'nullable|date',
            'importe_de_estimacion' => 'nullable|numeric|min:0',
            'iva' => 'nullable|numeric|min:0',
            'retenciones_o_sanciones' => 'nullable|numeric|min:0',
            'total_estimacion_con_iva' => 'nullable|numeric|min:0',
            'fecha_elaboracion' => 'nullable|date',
            'avance_obra_estimacion' => 'nullable|numeric|min:0|max:100',
            'avance_obra_real' => 'nullable|numeric|min:0|max:100',
            'porcentaje_avance_financiero' => 'nullable|numeric|min:0|max:100',
            'cargos_adicionales_35_porciento' => 'nullable|numeric|min:0',
            'retencion_5_al_millar' => 'nullable|numeric|min:0',
            'sancion_atraso_presentacion_estimacion' => 'nullable|numeric|min:0',
            'sancion_atraso_de_obra' => 'nullable|numeric|min:0',
            'sancion_por_obra_mal_ejecutada' => 'nullable|numeric|min:0',
            'retencion_por_atraso_en_programa_de_obra' => 'nullable|numeric|min:0',
            'amortizacion_anticipo' => 'nullable|numeric|min:0',
            'amortizacion_con_iva' => 'nullable|numeric|min:0',
            'total_deducciones' => 'nullable|numeric|min:0',
            'importe_facturado' => 'nullable|numeric|min:0',
            'liquido_a_cobrar' => 'nullable|numeric|min:0',
            'liquido_cobrado' => 'nullable|numeric|min:0',
            'fecha_cobro' => 'nullable|date',
            'por_cobrar' => 'nullable|numeric|min:0',
            'por_facturar' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:pendiente,pagado,parcial,cancelado',
            'estimado_menos_deducciones' => 'nullable|numeric',
            'verificado' => 'nullable|integer',
        ]);
        
        // Generar ID único usando la función helper GetUuid()
        $validated['id'] = GetUuid();
        
        // Calcular total deducciones si no se proporcionó
        if (!isset($validated['total_deducciones']) || $validated['total_deducciones'] === null) {
            $deducciones = [
                'retenciones_o_sanciones',
                'cargos_adicionales_35_porciento',
                'retencion_5_al_millar',
                'sancion_atraso_presentacion_estimacion',
                'sancion_atraso_de_obra',
                'sancion_por_obra_mal_ejecutada',
                'retencion_por_atraso_en_programa_de_obra',
                'amortizacion_anticipo',
                'amortizacion_con_iva'
            ];
            
            $totalDeducciones = 0;
            foreach ($deducciones as $deduccion) {
                $totalDeducciones += $validated[$deduccion] ?? 0;
            }
            $validated['total_deducciones'] = $totalDeducciones;
        }
        
        // Calcular estimado menos deducciones si no se proporcionó
        if (!isset($validated['estimado_menos_deducciones']) || $validated['estimado_menos_deducciones'] === null) {
            $importeEstimacion = $validated['importe_de_estimacion'] ?? 0;
            $iva = $validated['iva'] ?? 0;
            $totalConIva = $importeEstimacion + $iva;
            $validated['estimado_menos_deducciones'] = $totalConIva - ($validated['total_deducciones'] ?? 0);
        }
        
        // Calcular por cobrar si no se proporcionó
        if (!isset($validated['por_cobrar']) || $validated['por_cobrar'] === null) {
            $validated['por_cobrar'] = ($validated['liquido_a_cobrar'] ?? 0) - ($validated['liquido_cobrado'] ?? 0);
        }
        
        // Crear el ingreso
        $ingreso = Ingreso::create($validated);
        
        return redirect()->route('ingresos.show', $ingreso->id)
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
        
        return view('administradores.ingresos.show', compact('ingreso', 'contrato', 'contratos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, $id)
    {
        $ingreso = Ingreso::findOrFail($id);
        
        // Solo cambia el puto campo verificado
        $ingreso->verificado = $request->verificado;
        $ingreso->save();
        
        return back()->with('success', 'Listo.');
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
            return redirect()->route('ingresos.index')
                ->with('error', 'No se puede eliminar el ingreso porque ya está verificado.');
        }
        
        $ingreso->delete();
        
        return redirect()->route('ingresos.index')
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
}