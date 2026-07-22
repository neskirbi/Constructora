<?php

namespace App\Http\Controllers\Acompras;

use App\Http\Controllers\Controller;
use App\Models\Requisicion;
use App\Models\Contrato;
use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\ProductoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ProveedorSer;
use App\Models\Proveedor;
use App\Services\GeminiExcelService;
use App\Models\Inventario;
use App\Models\RequisicionDetalle;
use App\Models\ProductoProveedor;

class RequisicionController extends Controller
{
    public function index(Request $request)
{
    $sessionId = GetId();
    
    $query = Requisicion::where('session_id', $sessionId)
        ->with('contrato', 'detalles');
    
    // Búsqueda
    $search = $request->search;
    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('consecutivo', 'LIKE', "%{$search}%")
              ->orWhere('frente', 'LIKE', "%{$search}%")
              ->orWhere('empresa', 'LIKE', "%{$search}%")
              ->orWhereHas('contrato', function($sub) use ($search) {
                  $sub->where('consecutivo', 'LIKE', "%{$search}%")
                      ->orWhere('refinterna', 'LIKE', "%{$search}%");
              });
        });
    }
    
    $requisiciones = $query->orderBy('created_at', 'desc')
        ->paginate(10);
    
    $contratos = Contrato::orderBy('consecutivo', 'desc')
        ->select('id', 'consecutivo', 'contrato_no', 'refinterna', 'obra')
        ->limit(100)
        ->get();
    
    $proveedores = ProveedorSer::orderBy('clave')->get();
    
    return view('acompras.requisiciones.index', compact('requisiciones', 'contratos', 'proveedores', 'search'));
}

    public function create()
    {
        $contratos = Contrato::orderBy('consecutivo', 'desc')
            ->select('id', 'consecutivo', 'contrato_no', 'refinterna', 'obra')
            ->limit(100)
            ->get();
        
        return view('acompras.requisiciones.create', compact('contratos'));
    }

   public function show($id)
{
    $sessionId = GetId();
    
    $requisicion = Requisicion::where('session_id', $sessionId)
        ->where('id', $id)
        ->with('contrato', 'detalles')
        ->firstOrFail();
    
    $contrato = $requisicion->contrato;
    $items = $requisicion->detalles()->with('proveedores')->get();
    $proveedores = Proveedor::orderBy('clave')->get();
    
    return view('acompras.requisiciones.show', compact('requisicion', 'contrato', 'items', 'proveedores'));
}

    public function procesarExcel(Request $request)
{
    $request->validate([
        'archivo_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    ]);
    
    $sessionId = GetId();
    $logs = [];
    
    try {
        $geminiService = new GeminiExcelService();
        $resultado = $geminiService->procesarExcel($request->file('archivo_excel'));
        
        $logs = $geminiService->getLogs();
        
        if (empty($resultado)) {
            return redirect()->route('compras.requisiciones.create')
                ->with('error', 'La IA no pudo extraer datos del Excel. Verifica el formato.')
                ->with('logs', $logs);
        }
        
        // Buscar el contrato por contrato_no
        $contratoNo = (string) $resultado['contrato_no'] ?? null;

        
        if (empty($contratoNo)) {
            return redirect()->route('compras.requisiciones.create')
                ->with('error', 'No se encontró el número de contrato en el Excel.')
                ->with('logs', $logs);
        }
        //$contratoNo = floatval($contratoNo);
        $contrato = Contrato::where('consecutivo', 'like', "%{$contratoNo}%")->first();
        
        if (!$contrato) {
            return redirect()->route('compras.requisiciones.create')
                ->with('error', 'Contrato no encontrado: ' . $contratoNo)
                ->with('logs', $logs);
        }
        
        // VALIDAR QUE EXISTAN ITEMS
        $items = $resultado['items'] ?? [];
        
        if (empty($items)) {
            return redirect()->route('compras.requisiciones.create')
                ->with('error', 'No se encontraron productos en el Excel. Verifica que la tabla tenga las columnas: Clave, Cantidad')
                ->with('logs', $logs);
        }
        
        // Crear la requisición
        $requisicion = Requisicion::create([
            'id' => GetUuid(),
            'session_id' => $sessionId,
            'consecutivo' => Requisicion::where('session_id', $sessionId)->max('consecutivo') + 1,
            'fecha_solicitud' => now(),
            'fecha_entrega' => $resultado['fecha_entrega'] ?? null,
            'frente' => $contrato->frente ?? null,
            'contrato_id' => $contrato->id,
            'empresa' => $contrato->empresa ?? null,
            'proyecto' => $contrato->obra ?? null,
            'cliente' => $contrato->cliente ?? null,
            'contratista' => $resultado['contratista'] ?? null,
            'partida' => $resultado['partida'] ?? null,
            'direccion_entrega' => $contrato->lugar ?? null,
        ]);
        
        $itemsAgregados = 0;
        $itemsErroneos = 0;
        $errores = [];
        
        foreach ($items as $item) {
            $clave = $item['clave'] ?? '';
            $cantidad = floatval($item['cantidad'] ?? 0);
            $link = $item['link'] ?? '';
            $observaciones = $item['observaciones'] ?? '';
            
            if (empty($clave) || $cantidad <= 0) {
                $itemsErroneos++;
                $errores[] = "Datos incompletos: " . json_encode($item);
                continue;
            }
            
            $producto = Inventario::where('clave', $clave)->first();
            
            if (!$producto) {
                $itemsErroneos++;
                $errores[] = "Producto no encontrado en catálogo: $clave";
                continue;
            }
            
            $descripcion = $producto->descripcion;
            $unidad = $producto->unidad;
            $precio = $producto->ult_costo ?? 0;
            
            $detalle = $requisicion->detalles()->create([
                'id' => GetUuid(),
                'clave' => $clave,
                'descripcion' => $descripcion,
                'unidad' => $unidad,
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
                'observaciones' => $observaciones,
                'link' => $link,
            ]);
            
            if ($precio > 0) {
                $detalle->subtotal = $cantidad * $precio;
                $detalle->iva = $detalle->subtotal * 0.16;
                $detalle->total = $detalle->subtotal + $detalle->iva;
                $detalle->save();
            }
            
            $itemsAgregados++;
        }
        
        // SI NO SE AGREGÓ NINGÚN ITEM, ELIMINAR LA REQUISICIÓN Y MANDAR ERROR
        if ($itemsAgregados == 0) {
            $requisicion->delete();
            
            $mensajeError = 'No se pudo agregar ningún producto. ';
            if ($itemsErroneos > 0) {
                $mensajeError .= implode(' | ', $errores);
            } else {
                $mensajeError .= 'Verifica que las claves existan en el catálogo.';
            }
            
            return redirect()->route('compras.requisiciones.create')
                ->with('error', $mensajeError)
                ->with('logs', $logs)
                ->with('itemsErroneos', $errores);
        }
        
        $mensaje = "Requisición creada con IA. Se agregaron $itemsAgregados items.";
        if ($itemsErroneos > 0) {
            $mensaje .= " $itemsErroneos items con errores.";
        }
        
        return redirect()->route('compras.requisiciones.show', $requisicion->id)
            ->with('success', $mensaje)
            ->with('logs', $logs)
            ->with('itemsErroneos', $errores);
            
    } catch (\Exception $e) {
        Log::error('Error en procesarExcel: ' . $e->getMessage());
        
        return redirect()->route('compras.requisiciones.create')
            ->with('error', 'Error al procesar: ' . $e->getMessage())
            ->with('logs', $logs);
    }
}
    
    public function eliminarItem(Request $request)
    {
        $id = $request->id;
        $item = Requisicion::where('session_id', GetId())
            ->where('id', $id)
            ->firstOrFail();
        
        $item->delete();
        
        return response()->json(['success' => true]);
    }
    
    public function vaciarCarrito()
    {
        Requisicion::where('session_id', GetId())->delete();
        
        return response()->json(['success' => true]);
    }
    
    public function confirmarCompra(Request $request, $id)
    {
        $sessionId = GetId();
        
        $requisicion = Requisicion::where('session_id', $sessionId)
            ->where('id', $id)
            ->with('detalles')
            ->firstOrFail();
        
        $items = $requisicion->detalles;
        
        if ($items->isEmpty()) {
            return redirect()->route('compras.requisiciones.show', $id)
                ->with('error', 'No hay items en esta requisición.');
        }
        
        $sinPrecio = $items->filter(function($item) {
            return empty($item->precio_unitario) || $item->precio_unitario == 0;
        });
        
        if ($sinPrecio->count() > 0) {
            $claves = $sinPrecio->pluck('clave')->implode(', ');
            return redirect()->route('compras.requisiciones.show', $id)
                ->with('error', "Items sin precio: {$claves}");
        }
        
        $contrato = $requisicion->contrato;
        
        try {
            DB::beginTransaction();
            
            $id_usuario = GetId();
            $id_proveedor = $request->id_proveedor ?? null;
            
            if (empty($id_proveedor)) {
                $proveedor = ProveedorSer::first();
                if ($proveedor) {
                    $id_proveedor = $proveedor->id;
                } else {
                    $proveedor = ProveedorSer::create([
                        'id' => GetUuid(),
                        'nombre' => 'Proveedor por definir',
                        'empresa' => $contrato->empresa ?? '',
                    ]);
                    $id_proveedor = $proveedor->id;
                }
            }
            
            $compra = Compra::create([
                'id' => GetUuid(),
                'numeracion' => Compra::max('numeracion') + 1,
                'id_contrato' => $contrato->id ?? null,
                'id_usuario' => $id_usuario,
                'id_proveedor' => $id_proveedor,
                'consecutivo' => $request->consecutivo ?? null,
                'referencia' => $request->referencia ?? null,
                'costo_operado' => $items->sum('subtotal'),
                'iva' => $items->sum('iva'),
                'total' => $items->sum('total'),
                'metodo_pago' => $request->metodo_pago ?? null,
                'empresa_pago' => $request->empresa_pago ?? null,
                'verificado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            foreach ($items as $index => $item) {
                $producto = Inventario::where('clave', $item->clave)->first();
                $id_producto = $producto ? $producto->id : null;
                
                DB::table('compradetalle')->insert([
                    'id' => GetUuid(),
                    'id_compra' => $compra->id,
                    'id_productoservicio' => $id_producto,
                    'clave' => $item->clave,
                    'descripcion' => $item->descripcion,
                    'unidades' => $item->unidad,
                    'cantidad' => $item->cantidad,
                    'descuento_porcentaje' => 0,
                    'descuento_monto' => 0,
                    'ult_costo' => $item->precio_unitario,
                    'fecha_entrega' => null,
                    'tipo_entrega' => null,
                    'comentarios' => null,
                    'orden' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Marcar requisición como procesada (no la borramos)
            $requisicion->procesada = 1;
            $requisicion->compra_id = $compra->id;
            $requisicion->save();
            
            DB::commit();
            
            return redirect()->route('compras.show', $compra->id)
                ->with('success', "Compra #{$compra->numeracion} creada exitosamente.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear compra: ' . $e->getMessage());
            return redirect()->route('compras.requisiciones.show', $id)
                ->with('error', 'Error al crear la compra: ' . $e->getMessage());
        }
    }
    
    public function agregarProveedor(Request $request)
    {
        $request->validate([
            'requisicion_id' => 'required|exists:requisiciones,id',
            'proveedor_id' => 'required|exists:proveedores_servicios,id',
            'monto' => 'required|numeric|min:0',
        ]);
        
        $requisicionProveedor = RequisicionProveedor::create([
            'id' => GetUuid(),
            'requisicion_id' => $request->requisicion_id,
            'proveedor_id' => $request->proveedor_id,
            'monto' => $request->monto,
        ]);
        
        $proveedor = ProveedorSer::find($request->proveedor_id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $requisicionProveedor->id,
                'proveedor' => $proveedor->clave . ' - ' . $proveedor->nombre,
                'monto' => $requisicionProveedor->monto,
            ]
        ]);
    }
    
    public function eliminarProveedor($id)
    {
        $requisicionProveedor = RequisicionProveedor::findOrFail($id);
        $requisicionProveedor->delete();
        
        return response()->json(['success' => true]);
    }


    public function guardarItemCompleto(Request $request)
{
    try {
        $sessionId = GetId();
        
        // Actualizar la cantidad del item
        $detalle = RequisicionDetalle::where('id', $request->id)
            ->whereHas('requisicion', function($q) use ($sessionId) {
                $q->where('session_id', $sessionId);
            })
            ->firstOrFail();
        
        $detalle->cantidad = $request->cantidad;
        $detalle->save();
        
        // Guardar proveedores
        $proveedoresGuardados = [];
        foreach ($request->proveedores as $proveedorData) {
            // Verificar si el proveedor ya existe
            $existente = ProductoProveedor::where('detalle_id', $request->id)
                ->where('proveedor_id', $proveedorData['proveedor_id'])
                ->first();
            
            if ($existente) {
                // Actualizar existente
                $existente->precio = $proveedorData['precio'];
                $existente->descuento = $proveedorData['descuento'];
                $existente->save();
                $proveedoresGuardados[] = $existente;
            } else {
                // Crear nuevo
                $nuevo = ProductoProveedor::create([
                    'id' => GetUuid(),
                    'detalle_id' => $request->id,
                    'proveedor_id' => $proveedorData['proveedor_id'],
                    'precio' => $proveedorData['precio'],
                    'descuento' => $proveedorData['descuento'],
                ]);
                $proveedoresGuardados[] = $nuevo;
            }
        }
        
        // Recalcular totales del detalle
        $mejorPrecio = $detalle->proveedores()->orderBy('precio', 'asc')->first();
        if ($mejorPrecio) {
            $detalle->precio_unitario = $mejorPrecio->precio;
            $detalle->descuento = $mejorPrecio->descuento;
            $detalle->subtotal = $detalle->cantidad * $mejorPrecio->precio;
            $detalle->descuento_monto = ($detalle->subtotal * $mejorPrecio->descuento) / 100;
            $detalle->iva = ($detalle->subtotal - $detalle->descuento_monto) * 0.16;
            $detalle->total = ($detalle->subtotal - $detalle->descuento_monto) + $detalle->iva;
            $detalle->save();
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'proveedores' => $proveedoresGuardados
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function eliminarProveedorItem($id)
{
    try {
        $proveedor = ProductoProveedor::findOrFail($id);
        $proveedor->delete();
        
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function resumen($id)
{
    try {
        $sessionId = GetId();
        
        $requisicion = Requisicion::where('id', $id)
            ->where('session_id', $sessionId)
            ->with('detalles')
            ->firstOrFail();
        
        $items = $requisicion->detalles;
        
        return response()->json([
            'subtotal' => $items->sum('subtotal'),
            'descuento' => $items->sum('descuento_monto'),
            'iva' => $items->sum('iva'),
            'total' => $items->sum('total')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}