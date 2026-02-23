<?php

namespace App\Http\Controllers\Administradores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\ProveedorSer;
use App\Models\Contrato;
use App\Models\ProductosYServicios;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = DB::table('compras as c')
            ->leftJoin('contratos as ct', 'c.id_contrato', '=', 'ct.id')
            ->leftJoin('proveedores_servicios as p', 'c.id_proveedor', '=', 'p.id')
            ->leftJoin('acompras as u', 'c.id_usuario', '=', 'u.id')
            ->select(
                'c.*',
                'ct.contrato_no',
                'ct.obra as contrato_obra',
                'p.nombre as proveedor_nombre',
                'p.clave as proveedor_clave',
                'u.nombres as usuario_nombres',
                'u.apellidos as usuario_apellidos'
            );
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('c.referencia', 'like', '%' . $search . '%')
                  ->orWhere('ct.contrato_no', 'like', '%' . $search . '%')
                  ->orWhere('p.nombre', 'like', '%' . $search . '%')
                  ->orWhere('p.clave', 'like', '%' . $search . '%');
            });
        }
        
        $compras = $query->orderBy('c.created_at', 'desc')
            ->paginate(15);
        
        // Obtener todos los IDs de compras
        $compraIds = $compras->pluck('id')->toArray();
        
        if (!empty($compraIds)) {
            // Obtener todos los detalles de una sola vez
            $todosDetalles = DB::table('compradetalle')
                ->whereIn('id_compra', $compraIds)
                ->get()
                ->groupBy('id_compra');
            
            // Asignar los detalles a cada compra
            foreach ($compras as $compra) {
                $compra->detalles = $todosDetalles[$compra->id] ?? collect([]);
            }
        } else {
            foreach ($compras as $compra) {
                $compra->detalles = collect([]);
            }
        }
        
        return view('administradores.compras.index', compact('compras', 'search'));
    }

    
    public function create()
    {
        // Obtener el último consecutivo para sugerir el siguiente
        $ultimoConsecutivo = Compra::max('consecutivo') ?? 0;
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
        
        return view('administradores.compras.create', compact(
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
        $id_usuario = auth('acompras')->id();
        
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
            // Crear la compra principal
            $compra_id = GetUuid();
            
            DB::table('compras')->insert([
                'id' => $compra_id,
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
                
                DB::table('compradetalle')->insert([
                    'id' => GetUuid(),
                    'id_compra' => $compra_id,
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
            
            return redirect()->route('acompras.index')
                ->with('success', 'Compra creada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error al crear la compra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Obtener la compra con datos relacionados mediante JOIN
        $compra = DB::table('compras as c')
            ->leftJoin('contratos as ct', 'c.id_contrato', '=', 'ct.id')
            ->leftJoin('proveedores_servicios as p', 'c.id_proveedor', '=', 'p.id')
            ->leftJoin('acompras as u', 'c.id_usuario', '=', 'u.id')
            ->where('c.id', $id)
            ->select(
                'c.*',
                'ct.contrato_no',
                'ct.obra as contrato_obra',
                'ct.cliente as contrato_cliente',
                'p.nombre as proveedor_nombre',
                'p.clave as proveedor_clave',
                'p.telefono as proveedor_telefono',
                'u.nombres as usuario_nombres',
                'u.apellidos as usuario_apellidos',
                'u.mail as usuario_email'
            )
            ->first();
        
        if (!$compra) {
            abort(404);
        }
        
        // Obtener los detalles de la compra
        $detalles = DB::table('compradetalle')
            ->where('id_compra', $id)
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
        
        return view('administradores.compras.show', compact('compra', 'detalles', 'proveedores', 'contratos', 'productos'));
    }

    public function edit($id)
    {
        // Obtener la compra
        $compra = DB::table('compras')
            ->where('id', $id)
            ->first();
        
        if (!$compra) {
            abort(404);
        }
        
        // Verificar si se puede editar
        if (isset($compra->verificado) && $compra->verificado != 1) {
            return redirect()->route('acompras.index')
                ->with('error', 'No se puede editar una compra que no está pendiente');
        }
        
        // Obtener los detalles de la compra
        $detalles = DB::table('compradetalle')
            ->where('id_compra', $id)
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
        
        return view('administradores.compras.edit', compact(
            'compra', 
            'detalles', 
            'proveedores', 
            'contratos', 
            'productos'
        ));
    }

    public function update(Request $request, $id)
    {
        // Verificar si la compra existe
        $compra = DB::table('compras')
            ->where('id', $id)
            ->first();
        
        if (!$compra) {
            abort(404);
        }
        
        if (isset($compra->verificado) && $compra->verificado != 1) {
            return redirect()->route('acompras.index')
                ->with('error', 'No se puede editar una compra que no está pendiente');
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
            // Actualizar compra principal
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
            
            DB::table('compras')
                ->where('id', $id)
                ->update($updateData);
            
            // Eliminar detalles antiguos
            DB::table('compradetalle')
                ->where('id_compra', $id)
                ->delete();
            
            // Insertar nuevos detalles con CANTIDAD
            foreach ($request->productos as $producto) {
                $productoData = DB::table('productosyservicios')
                    ->where('id', $producto['id_producto'])
                    ->first();
                
                DB::table('compradetalle')->insert([
                    'id' => GetUuid(),
                    'id_compra' => $id,
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
            
            return redirect('compras/' . $id)
                ->with('success', 'Compra actualizada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error al actualizar la compra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Verificar si la compra existe
        $compra = DB::table('compras')
            ->where('id', $id)
            ->first();
        
        if (!$compra) {
            return redirect()->route('acompras.index')
                ->with('error', 'Compra no encontrada');
        }
        
        DB::beginTransaction();
        
        try {
            // Eliminar detalles primero
            DB::table('compradetalle')
                ->where('id_compra', $id)
                ->delete();
            
            // Eliminar compra
            DB::table('compras')
                ->where('id', $id)
                ->delete();
            
            DB::commit();
            
            return redirect()->route('acompras.index')
                ->with('success', 'Compra eliminada exitosamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('acompras.index')
                ->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }

    /**
     * Confirmar (aprobar) una compra
     */
    public function confirmar($id)
    {
        // Verificar si la compra existe
        $compra = DB::table('compras')
            ->where('id', $id)
            ->first();
        
        if (!$compra) {
            return redirect()->route('acompras.index')
                ->with('error', 'Compra no encontrada');
        }
        
        try {
            DB::table('compras')
                ->where('id', $id)
                ->update([
                    'verificado' => 2, // 2 = Aprobado
                    'updated_at' => now()
                ]);
            
            return redirect()->route('acompras.show', $id)
                ->with('success', 'Compra confirmada exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->route('acompras.show', $id)
                ->with('error', 'Error al confirmar la compra: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar una compra
     */
    public function rechazar($id)
    {
        // Verificar si la compra existe
        $compra = DB::table('compras')
            ->where('id', $id)
            ->first();
        
        if (!$compra) {
            return redirect()->route('acompras.index')
                ->with('error', 'Compra no encontrada');
        }
        
        try {
            DB::table('compras')
                ->where('id', $id)
                ->update([
                    'verificado' => 0, // 0 = Rechazado
                    'updated_at' => now()
                ]);
            
            return redirect()->route('acompras.show', $id)
                ->with('success', 'Compra rechazada exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->route('acompras.show', $id)
                ->with('error', 'Error al rechazar la compra: ' . $e->getMessage());
        }
    }
}