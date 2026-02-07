<?php

namespace App\Http\Controllers\Adestajos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destajo;
use App\Models\ProveedorSer;
use App\Models\Contrato;

class DestajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $destajos = Destajo::query()
            ->when($search, function ($query, $search) {
                return $query->where('clave_concepto', 'like', '%' . $search . '%')
                    ->orWhere('descripcion_concepto', 'like', '%' . $search . '%')
                    ->orWhere('referencia', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('adestajos.destajos.index', compact('destajos', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
{
    // Obtener el último consecutivo para sugerir el siguiente
    $ultimoConsecutivo = Destajo::max('consecutivo') ?? 0;
    $siguienteConsecutivo = $ultimoConsecutivo + 1;
    
    // Obtener proveedores activos para select
    $proveedores = ProveedorSer::where('estatus', 'Activo')
        ->orderBy('nombre')
        ->get();
    
    // Obtener contratos activos para select
    $contratos = Contrato::orderBy('contrato_no')
        ->get();
    
    // Unidades exactas según tu lista
    $unidades = [
        'pza', 'Ton', 'Kg', 'Servicio', 'm3', 'm2', 'm',
        'Jornada', 'Semana', 'Lote', 'Viaje', 'Cubeta',
        'Rollo', 'Salida'
    ];
    
    return view('adestajos.destajos.create', compact(
        'siguienteConsecutivo',
        'proveedores',
        'contratos',
        'unidades'
    ));
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'consecutivo' => 'required|integer|min:1|unique:destajos,consecutivo',
        'id_contrato' => 'required|string|exists:contratos,id',
        'id_proveedor' => 'required|string|exists:proveedores_servicios,id',
        'clave_concepto' => 'required|string|max:50',
        'descripcion_concepto' => 'required|string|max:500',
        'unidad_concepto' => 'required|string|max:50',
        'costo_unitario_concepto' => 'required|numeric|min:0',
        'cantidad' => 'required|numeric|min:0.01',
        'referencia' => 'nullable|string|max:1500',
        'iva' => 'nullable|numeric|min:0',
    ]);
    
    // Calcular valores
    $costo_unitario = $request->costo_unitario_concepto;
    $cantidad = $request->cantidad;
    $iva_porcentaje = $request->iva ?? 0;
    
    $costo_operado = $costo_unitario * $cantidad;
    $iva_calculado = $costo_operado * ($iva_porcentaje / 100);
    $total = $costo_operado + $iva_calculado;
    
    // Obtener el ID del usuario autenticado
    $id_usuario = auth('adestajos')->id();
    
    $destajo = new Destajo();
    $destajo->id = GetUuid();
    $destajo->id_contrato = $request->id_contrato;
    $destajo->id_usuario = $id_usuario;
    $destajo->id_proveedor = $request->id_proveedor;
    $destajo->consecutivo = $request->consecutivo;
    $destajo->clave_concepto = $request->clave_concepto;
    $destajo->descripcion_concepto = $request->descripcion_concepto;
    $destajo->unidad_concepto = $request->unidad_concepto;
    $destajo->costo_unitario_concepto = $costo_unitario;
    $destajo->cantidad = $cantidad;
    $destajo->referencia = $request->referencia;
    $destajo->costo_operado = $costo_operado;
    $destajo->iva = $iva_calculado;
    $destajo->total = $total;
    
    $destajo->save();
    
    return redirect()->route('destajos.index')
        ->with('success', 'Destajo creado exitosamente');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */public function show($id)
    {
        $destajo = Destajo::findOrFail($id);
        
        // Obtener proveedores activos para select
        $proveedores = ProveedorSer::where('estatus', 'Activo')
            ->orderBy('nombre')
            ->get();
        
        // Obtener contratos activos para select
    $contratos = Contrato::orderBy('contrato_no')
        ->get();
    
        // Unidades exactas según tu lista
        $unidades = [
            'pza', 'Ton', 'Kg', 'Servicio', 'm3', 'm2', 'm',
            'Jornada', 'Semana', 'Lote', 'Viaje', 'Cubeta',
            'Rollo', 'Salida'
        ];
        
        return view('adestajos.destajos.show', compact(
            'destajo',
            'proveedores',
            'contratos',
            'unidades'
        ));
    }

public function update(Request $request, $id)
{
    $destajo = Destajo::findOrFail($id);
    
    // Verificar si se puede editar (solo si verificado == 1 o no existe el campo)
    if (isset($destajo->verificado) && $destajo->verificado != 1) {
        return redirect()->route('destajos.index')
            ->with('error', 'No se puede editar un destajo que no está pendiente');
    }
    
    $request->validate([
        'consecutivo' => 'required|integer|min:1|unique:destajos,consecutivo,' . $destajo->id,
        'id_contrato' => 'required|string|exists:contratos,id',
        'id_proveedor' => 'required|string|exists:proveedores_servicios,id',
        'clave_concepto' => 'required|string|max:50',
        'descripcion_concepto' => 'required|string|max:500',
        'unidad_concepto' => 'required|string|max:50',
        'costo_unitario_concepto' => 'required|numeric|min:0',
        'cantidad' => 'required|numeric|min:0.01',
        'referencia' => 'nullable|string|max:1500',
        'iva' => 'nullable|numeric|min:0',
        'verificado' => 'nullable|integer|in:0,1,2',
    ]);
    
    // Calcular valores
    $costo_unitario = $request->costo_unitario_concepto;
    $cantidad = $request->cantidad;
    $iva_porcentaje = $request->iva ?? 0;
    
    $costo_operado = $costo_unitario * $cantidad;
    $iva_calculado = $costo_operado * ($iva_porcentaje / 100);
    $total = $costo_operado + $iva_calculado;
    
    // Actualizar destajo
    $destajo->id_contrato = $request->id_contrato;
    $destajo->id_proveedor = $request->id_proveedor;
    $destajo->clave_concepto = $request->clave_concepto;
    $destajo->descripcion_concepto = $request->descripcion_concepto;
    $destajo->unidad_concepto = $request->unidad_concepto;
    $destajo->costo_unitario_concepto = $costo_unitario;
    $destajo->cantidad = $cantidad;
    $destajo->referencia = $request->referencia;
    $destajo->costo_operado = $costo_operado;
    $destajo->iva = $iva_calculado;
    $destajo->total = $total;
    
    // Solo actualizar verificado si se envió en el formulario
    if ($request->has('verificado')) {
        $destajo->verificado = $request->verificado;
    }
    
    $destajo->save();
    
    return redirect('destajos/'.$destajo->id)
        ->with('success', 'Destajo actualizado exitosamente');
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
