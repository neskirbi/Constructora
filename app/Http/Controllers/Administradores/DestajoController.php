<?php

namespace App\Http\Controllers\Administradores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destajo;
use App\Models\DestajoDetalle;
use App\Models\ProveedorSer;
use App\Models\Contrato;
use App\Models\ProductosYServicios;
use Illuminate\Support\Facades\DB;

class DestajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = DB::table('destajos as d')
            ->leftJoin('contratos as c', 'd.id_contrato', '=', 'c.id')
            ->leftJoin('proveedores_servicios as p', 'd.id_proveedor', '=', 'p.id')
            ->leftJoin('adestajos as u', 'd.id_usuario', '=', 'u.id')
            ->select(
                'd.*',
                'c.contrato_no',
                'c.obra as contrato_obra',
                'p.nombre as proveedor_nombre',
                'p.clave as proveedor_clave',
                'u.nombres as usuario_nombres',
                'u.apellidos as usuario_apellidos'
            );
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('d.referencia', 'like', '%' . $search . '%')
                  ->orWhere('c.contrato_no', 'like', '%' . $search . '%')
                  ->orWhere('p.nombre', 'like', '%' . $search . '%')
                  ->orWhere('p.clave', 'like', '%' . $search . '%');
            });
        }
        
        $destajos = $query->orderBy('d.created_at', 'desc')
            ->paginate(15);
        
        // Obtener todos los IDs de destajos
        $destajoIds = $destajos->pluck('id')->toArray();
        
        if (!empty($destajoIds)) {
            // Obtener todos los detalles de una sola vez
            $todosDetalles = DB::table('destajodetalles')
                ->whereIn('id_destajo', $destajoIds)
                ->get()
                ->groupBy('id_destajo');
            
            // Asignar los detalles a cada destajo
            foreach ($destajos as $destajo) {
                $destajo->detalles = $todosDetalles[$destajo->id] ?? collect([]);
            }
        } else {
            foreach ($destajos as $destajo) {
                $destajo->detalles = collect([]);
            }
        }
        
        return view('administradores.destajos.index', compact('destajos', 'search'));
    }

    
    public function create()
    {
        // Obtener el último consecutivo para sugerir el siguiente
        $ultimoConsecutivo = Destajo::max('consecutivo') ?? 0;
        $siguienteConsecutivo = $ultimoConsecutivo + 1;
        
        // Obtener proveedores activos para select
        $proveedores = DB::table('proveedores_servicios')
            ->where('estatus', 'Activo')
            ->orderBy('nombre')
            ->get();
        
        // Obtener contratos activos para select
        $contratos = DB::table('contratos')
            ->orderBy('contrato_no')
            ->get();
        
        // Obtener productos/servicios
        $productos = DB::table('productosyservicios')
            ->orderBy('clave')
            ->get();
        
        return view('administradores.destajos.create', compact(
            'siguienteConsecutivo',
            'proveedores',
            'contratos',
            'productos'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'consecutivo' => 'required|integer|min:1',
            'id_contrato' => 'required|string|exists:contratos,id',
            'id_proveedor' => 'required|string|exists:proveedores_servicios,id',
            'referencia' => 'nullable|string|max:1500',
            'iva' => 'nullable|numeric|min:0',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|string|exists:productosyservicios,id',
            'productos.*.cantidad' => 'required|numeric|min:0.01',
            'productos.*.precio' => 'required|numeric|min:0',
        ]);
        
        // Obtener el ID del usuario autenticado
        $id_usuario = auth('adestajos')->id();
        
        // Calcular el total de los productos
        $costo_operado = 0;
        foreach ($request->productos as $producto) {
            $costo_operado += $producto['cantidad'] * $producto['precio'];
        }
        
        $iva_porcentaje = $request->iva ?? 0;
        $iva_calculado = $costo_operado * ($iva_porcentaje / 100);
        $total = $costo_operado + $iva_calculado;
        
        DB::beginTransaction();
        
        try {
            // Crear el destajo principal
            $destajo_id = GetUuid();
            
            DB::table('destajos')->insert([
                'id' => $destajo_id,
                'id_contrato' => $request->id_contrato,
                'id_usuario' => $id_usuario,
                'id_proveedor' => $request->id_proveedor,
                'consecutivo' => $request->consecutivo,
                'referencia' => $request->referencia,
                'costo_operado' => $costo_operado,
                'iva' => $iva_calculado,
                'total' => $total,
                'verificado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Guardar los detalles con CANTIDAD
            foreach ($request->productos as $producto) {
                $productoData = DB::table('productosyservicios')
                    ->where('id', $producto['id_producto'])
                    ->first();
                
                DB::table('destajodetalles')->insert([
                    'id' => GetUuid(),
                    'id_destajo' => $destajo_id,
                    'id_productoservicio' => $producto['id_producto'],
                    'clave' => $productoData->clave,
                    'descripcion' => $productoData->descripcion,
                    'unidades' => $productoData->unidades,
                    'cantidad' => $producto['cantidad'],
                    'ult_costo' => $producto['precio'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('adestajos.index')
                ->with('success', 'Destajo creado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error al crear el destajo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Obtener el destajo con datos relacionados mediante JOIN
        $destajo = DB::table('destajos as d')
            ->leftJoin('contratos as c', 'd.id_contrato', '=', 'c.id')
            ->leftJoin('proveedores_servicios as p', 'd.id_proveedor', '=', 'p.id')
            ->leftJoin('adestajos as u', 'd.id_usuario', '=', 'u.id')
            ->where('d.id', $id)
            ->select(
                'd.*',
                'c.contrato_no',
                'c.obra as contrato_obra',
                'c.cliente as contrato_cliente',
                'p.nombre as proveedor_nombre',
                'p.clave as proveedor_clave',
                'p.telefono as proveedor_telefono',
                'u.nombres as usuario_nombres',
                'u.apellidos as usuario_apellidos',
                'u.mail as usuario_email'
            )
            ->first();
        
        if (!$destajo) {
            abort(404);
        }
        
        // Obtener los detalles del destajo
        $detalles = DB::table('destajodetalles')
            ->where('id_destajo', $id)
            ->get();
        
        // Obtener datos para los selects
        $proveedores = DB::table('proveedores_servicios')
            ->where('estatus', 'Activo')
            ->orderBy('nombre')
            ->get();
        
        $contratos = DB::table('contratos')
            ->orderBy('contrato_no')
            ->get();
        
        $productos = DB::table('productosyservicios')
            ->orderBy('clave')
            ->get();
        
        return view('administradores.destajos.show', compact('destajo', 'detalles', 'proveedores', 'contratos', 'productos'));
    }

    public function edit($id)
    {
        // Obtener el destajo
        $destajo = DB::table('destajos')
            ->where('id', $id)
            ->first();
        
        if (!$destajo) {
            abort(404);
        }
        
        // Verificar si se puede editar
        if (isset($destajo->verificado) && $destajo->verificado != 1) {
            return redirect()->route('adestajos.index')
                ->with('error', 'No se puede editar un destajo que no está pendiente');
        }
        
        // Obtener los detalles del destajo
        $detalles = DB::table('destajodetalles')
            ->where('id_destajo', $id)
            ->get();
        
        // Obtener datos para los selects
        $proveedores = DB::table('proveedores_servicios')
            ->where('estatus', 'Activo')
            ->orderBy('nombre')
            ->get();
        
        $contratos = DB::table('contratos')
            ->orderBy('contrato_no')
            ->get();
        
        $productos = DB::table('productosyservicios')
            ->orderBy('clave')
            ->get();
        
        return view('administradores.destajos.edit', compact(
            'destajo', 
            'detalles', 
            'proveedores', 
            'contratos', 
            'productos'
        ));
    }

    public function update(Request $request, $id)
    {
        // Verificar si el destajo existe
        $destajo = DB::table('destajos')
            ->where('id', $id)
            ->first();
        
        if (!$destajo) {
            abort(404);
        }
        
        if (isset($destajo->verificado) && $destajo->verificado != 1) {
            return redirect()->route('adestajos.index')
                ->with('error', 'No se puede editar un destajo que no está pendiente');
        }
        
        $request->validate([
            'consecutivo' => 'required|integer|min:1',
            'id_contrato' => 'required|string|exists:contratos,id',
            'id_proveedor' => 'required|string|exists:proveedores_servicios,id',
            'referencia' => 'nullable|string|max:1500',
            'iva' => 'nullable|numeric|min:0',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|string|exists:productosyservicios,id',
            'productos.*.cantidad' => 'required|numeric|min:0.01',
            'productos.*.precio' => 'required|numeric|min:0',
            'verificado' => 'nullable|integer|in:0,1,2',
        ]);
        
        // Calcular totales
        $costo_operado = 0;
        foreach ($request->productos as $producto) {
            $costo_operado += $producto['cantidad'] * $producto['precio'];
        }
        
        $iva_porcentaje = $request->iva ?? 0;
        $iva_calculado = $costo_operado * ($iva_porcentaje / 100);
        $total = $costo_operado + $iva_calculado;
        
        DB::beginTransaction();
        
        try {
            // Actualizar destajo principal
            $updateData = [
                'id_contrato' => $request->id_contrato,
                'id_proveedor' => $request->id_proveedor,
                'consecutivo' => $request->consecutivo,
                'referencia' => $request->referencia,
                'costo_operado' => $costo_operado,
                'iva' => $iva_calculado,
                'total' => $total,
                'updated_at' => now()
            ];
            
            if ($request->has('verificado')) {
                $updateData['verificado'] = $request->verificado;
            }
            
            DB::table('destajos')
                ->where('id', $id)
                ->update($updateData);
            
            // Eliminar detalles antiguos
            DB::table('destajodetalles')
                ->where('id_destajo', $id)
                ->delete();
            
            // Insertar nuevos detalles con CANTIDAD
            foreach ($request->productos as $producto) {
                $productoData = DB::table('productosyservicios')
                    ->where('id', $producto['id_producto'])
                    ->first();
                
                DB::table('destajodetalles')->insert([
                    'id' => GetUuid(),
                    'id_destajo' => $id,
                    'id_productoservicio' => $producto['id_producto'],
                    'clave' => $productoData->clave,
                    'descripcion' => $productoData->descripcion,
                    'unidades' => $productoData->unidades,
                    'cantidad' => $producto['cantidad'],
                    'ult_costo' => $producto['precio'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            DB::commit();
            
            return redirect('destajos/' . $id)
                ->with('success', 'Destajo actualizado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error al actualizar el destajo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Verificar si el destajo existe
        $destajo = DB::table('destajos')
            ->where('id', $id)
            ->first();
        
        if (!$destajo) {
            return response()->json([
                'success' => false,
                'message' => 'Destajo no encontrado'
            ], 404);
        }
        
        DB::beginTransaction();
        
        try {
            // Eliminar detalles primero
            DB::table('destajodetalles')
                ->where('id_destajo', $id)
                ->delete();
            
            // Eliminar destajo
            DB::table('destajos')
                ->where('id', $id)
                ->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Destajo eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el destajo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirmar (aprobar) un destajo
     */
    public function confirmar($id)
    {
        // Verificar si el destajo existe
        $destajo = DB::table('destajos')
            ->where('id', $id)
            ->first();
        
        if (!$destajo) {
            return redirect()->route('adestajos.index')
                ->with('error', 'Destajo no encontrado');
        }
        
        
        
        try {
            DB::table('destajos')
                ->where('id', $id)
                ->update([
                    'verificado' => 2, // 2 = Aprobado
                    'updated_at' => now()
                ]);
            
            return redirect()->route('adestajos.show', $id)
                ->with('success', 'Destajo confirmado exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->route('adestajos.show', $id)
                ->with('error', 'Error al confirmar el destajo: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar un destajo
     */
    public function rechazar($id)
    {
        // Verificar si el destajo existe
        $destajo = DB::table('destajos')
            ->where('id', $id)
            ->first();
        
        if (!$destajo) {
            return redirect()->route('adestajos.index')
                ->with('error', 'Destajo no encontrado');
        }
        
        
        
        try {
            DB::table('destajos')
                ->where('id', $id)
                ->update([
                    'verificado' => 0, // 0 = Rechazado
                    'updated_at' => now()
                ]);
            
            return redirect()->route('adestajos.show', $id)
                ->with('success', 'Destajo rechazado exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->route('adestajos.show', $id)
                ->with('error', 'Error al rechazar el destajo: ' . $e->getMessage());
        }
    }
}