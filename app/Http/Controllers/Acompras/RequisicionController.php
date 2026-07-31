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
use App\Services\GeminiExcelService;
use App\Models\RequisicionDetalle;
use App\Models\ProductoProveedor;

class RequisicionController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = GetId();
        
        $query = Requisicion::where('session_id', $sessionId)
            ->with('contrato', 'detalles');
        
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
    $proveedores = ProveedorSer::orderBy('clave')->get();
    
    // Obtener unidades distintas de la tabla productosyservicios
    $unidades = ProductoServicio::select('unidades')->distinct()->orderBy('unidades', 'asc')->pluck('unidades');
    
    return view('acompras.requisiciones.show', compact('requisicion', 'contrato', 'items', 'proveedores', 'unidades'));
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
        
        $noObra = (string) $resultado['no_obra'] ?? null;
        
        if (empty($noObra)) {
            return redirect()->route('compras.requisiciones.create')
                ->with('error', 'No se encontró el número de obra en el Excel.')
                ->with('logs', $logs);
        }
        
        $contrato = Contrato::where('consecutivo', 'like', "%{$noObra}%")->first();
        
        if (!$contrato) {
            return redirect()->route('compras.requisiciones.create')
                ->with('error', 'Contrato no encontrado para la obra: ' . $noObra)
                ->with('logs', $logs);
        }
        
        $items = $resultado['items'] ?? [];
        
        if (empty($items)) {
            return redirect()->route('compras.requisiciones.create')
                ->with('error', 'No se encontraron productos en el Excel. Verifica que la tabla tenga las columnas: Clave, Cantidad')
                ->with('logs', $logs);
        }
        
        $direccion = trim(
            ($contrato->calle_numero ?? '') . ' ' .
            ($contrato->colonia ?? '') . ', ' .
            ($contrato->codigo_postal ?? '') . ' ' .
            ($contrato->alcaldia_municipio ?? '') . ', ' .
            ($contrato->entidad ?? '')
        );

        $direccion = preg_replace('/\s+/', ' ', $direccion);
        $direccion = trim($direccion, ', ');

        // Validar fecha de entrega
        $fechaEntrega = $resultado['fecha_entrega'] ?? null;
        if ($fechaEntrega && strpos($fechaEntrega, '=') === 0) {
            $fechaEntrega = null;
        }

        // Agrupar items por partida
        $itemsPorPartida = [];
        foreach ($items as $item) {
            $partida = $item['partida'] ?? 'SIN PARTIDA';
            if (!isset($itemsPorPartida[$partida])) {
                $itemsPorPartida[$partida] = [];
            }
            $itemsPorPartida[$partida][] = $item;
        }
        
        $requisicionesCreadas = [];
        $totalItemsAgregados = 0;
        $totalItemsErroneos = 0;
        $todosErrores = [];
        
        foreach ($itemsPorPartida as $partida => $itemsPartida) {
            $consecutivo = Requisicion::where('session_id', $sessionId)->max('consecutivo') + 1;
            
            $requisicion = Requisicion::create([
                'id' => GetUuid(),
                'session_id' => $sessionId,
                'consecutivo' => $consecutivo,
                'fecha_solicitud' => now(),
                'fecha_entrega' => $fechaEntrega,
                'frente' => $contrato->frente ?? null,
                'contrato_id' => $contrato->id,
                'empresa' => $contrato->empresa ?? null,
                'proyecto' => $contrato->obra ?? null,
                'cliente' => $contrato->cliente ?? null,
                'contratista' => $resultado['contratista'] ?? null,
                'partida' => $partida,
                'direccion_entrega' => $direccion ?: null,
            ]);
            
            $itemsAgregados = 0;
            $itemsErroneos = 0;
            $errores = [];
            
            foreach ($itemsPartida as $item) {
                $clave = $item['clave'] ?? '';
                $cantidad = floatval($item['cantidad'] ?? 0);
                $unidad = $item['unidad'] ?? '';
                $link = $item['link'] ?? '';
                $observaciones = $item['observaciones'] ?? '';
                $partidaItem = $item['partida'] ?? $partida;
                
                if (empty($clave) || $cantidad <= 0) {
                    $itemsErroneos++;
                    $errores[] = "Datos incompletos: " . json_encode($item);
                    continue;
                }
                
                $producto = ProductoServicio::where('clave', $clave)->first();
                
                if (!$producto) {
                    $itemsErroneos++;
                    $errores[] = "Producto no encontrado en catálogo: $clave";
                    continue;
                }
                
                $descripcion = $producto->descripcion;
                
                $detalle = $requisicion->detalles()->create([
                    'id' => GetUuid(),
                    'producto_id' => $producto->id,
                    'clave' => $clave,
                    'descripcion' => $descripcion,
                    'unidad' => $unidad,
                    'cantidad' => $cantidad,
                    'partida' => $partidaItem,
                    'observaciones' => $observaciones,
                    'link' => $link,
                ]);
                
                $itemsAgregados++;
            }
            
            if ($itemsAgregados == 0) {
                $requisicion->delete();
                $todosErrores[] = "Partida '$partida': No se pudo agregar ningún producto. " . implode(' | ', $errores);
                $totalItemsErroneos += $itemsErroneos;
            } else {
                $requisicionesCreadas[] = $requisicion;
                $totalItemsAgregados += $itemsAgregados;
                $totalItemsErroneos += $itemsErroneos;
                $todosErrores = array_merge($todosErrores, $errores);
            }
        }
        
        if (empty($requisicionesCreadas)) {
            $mensajeError = 'No se pudo crear ninguna requisición. ';
            if (!empty($todosErrores)) {
                $mensajeError .= implode(' | ', array_slice($todosErrores, 0, 5));
                if (count($todosErrores) > 5) {
                    $mensajeError .= ' ... y ' . (count($todosErrores) - 5) . ' errores más.';
                }
            }
            
            return redirect()->route('compras.requisiciones.create')
                ->with('error', $mensajeError)
                ->with('logs', $logs)
                ->with('itemsErroneos', $todosErrores);
        }
        
        $mensaje = "Se crearon " . count($requisicionesCreadas) . " requisiciones con IA. ";
        $mensaje .= "Se agregaron $totalItemsAgregados items en total.";
        if ($totalItemsErroneos > 0) {
            $mensaje .= " $totalItemsErroneos items con errores.";
        }
        
        $primeraRequisicion = $requisicionesCreadas[0];
        
        return redirect()->route('compras.requisiciones.show', $primeraRequisicion->id)
            ->with('success', $mensaje)
            ->with('logs', $logs)
            ->with('itemsErroneos', $todosErrores)
            ->with('requisicionesCreadas', $requisicionesCreadas);
            
    } catch (\Exception $e) {
        Log::error('Error en procesarExcel: ' . $e->getMessage());
        
        return redirect()->route('compras.requisiciones.create')
            ->with('error', 'Error al procesar: ' . $e->getMessage())
            ->with('logs', $logs);
    }
}
    
    public function eliminarItem(Request $request)
    {
        try {
            $id = $request->id;
            
            // Buscar el detalle (no la requisición)
            $detalle = RequisicionDetalle::where('id', $id)
                ->whereHas('requisicion', function($q) {
                    $q->where('session_id', GetId());
                })
                ->firstOrFail();
            
            $requisicion = $detalle->requisicion;
            if ($requisicion->procesada == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar un item de una requisición procesada'
                ], 400);
            }
            
            // Eliminar el detalle (los proveedores se eliminan por CASCADE)
            $detalle->delete();
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
            ->with('detalles.proveedores')
            ->firstOrFail();
        
        $items = $requisicion->detalles;
        
        if ($items->isEmpty()) {
            return redirect()->route('compras.requisiciones.show', $id)
                ->with('error', 'No hay items en esta requisición.');
        }
        
        // Verificar que cada item tenga al menos un proveedor con precio
        foreach ($items as $item) {
            $proveedorSeleccionado = $item->proveedores->where('seleccionado', 1)->first();
            if (!$proveedorSeleccionado) {
                return redirect()->route('compras.requisiciones.show', $id)
                    ->with('error', "El item {$item->clave} no tiene un proveedor seleccionado.");
            }
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
            
            // Calcular totales desde producto_proveedores
            $subtotalTotal = 0;
            $descuentoTotal = 0;
            $ivaTotal = 0;
            $totalTotal = 0;
            
            foreach ($items as $item) {
                $proveedorSel = $item->proveedores->where('seleccionado', 1)->first();
                if ($proveedorSel) {
                    $subtotalItem = $item->cantidad * $proveedorSel->precio;
                    $descuentoItem = ($subtotalItem * $proveedorSel->descuento) / 100;
                    $subtotalConDescuento = $subtotalItem - $descuentoItem;
                    $ivaItem = $subtotalConDescuento * 0.16;
                    $totalItem = $subtotalConDescuento + $ivaItem;
                    
                    $subtotalTotal += $subtotalItem;
                    $descuentoTotal += $descuentoItem;
                    $ivaTotal += $ivaItem;
                    $totalTotal += $totalItem;
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
                'costo_operado' => $subtotalTotal - $descuentoTotal,
                'iva' => $ivaTotal,
                'total' => $totalTotal,
                'metodo_pago' => $request->metodo_pago ?? null,
                'empresa_pago' => $request->empresa_pago ?? null,
                'verificado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            foreach ($items as $index => $item) {
                $producto = ProductoServicio::where('clave', $item->clave)->first();
                $id_producto = $producto ? $producto->id : null;
                $proveedorSel = $item->proveedores->where('seleccionado', 1)->first();
                
                DB::table('compradetalle')->insert([
                    'id' => GetUuid(),
                    'id_compra' => $compra->id,
                    'id_productoservicio' => $id_producto,
                    'clave' => $item->clave,
                    'descripcion' => $item->descripcion,
                    'unidades' => $item->unidad,
                    'cantidad' => $item->cantidad,
                    'descuento_porcentaje' => $proveedorSel ? $proveedorSel->descuento : 0,
                    'descuento_monto' => $proveedorSel ? ($item->cantidad * $proveedorSel->precio * $proveedorSel->descuento) / 100 : 0,
                    'ult_costo' => $proveedorSel ? $proveedorSel->precio : 0,
                    'fecha_entrega' => null,
                    'tipo_entrega' => null,
                    'comentarios' => null,
                    'orden' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
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
    
    public function guardarItemCompleto(Request $request)
{
    $request->validate([
        'id' => 'required|string',
        'cantidad' => 'nullable|numeric',
        'inventario' => 'nullable|numeric',
        'cantidad_comprar' => 'nullable|numeric',
        'unidad_compra' => 'nullable|string',
        'proveedores' => 'required|array',
    ]);

    $detalle = RequisicionDetalle::findOrFail($request->id);
    
    // Guardar los campos
    $detalle->cantidad = $request->cantidad ?? $detalle->cantidad;
    $detalle->inventario = $request->inventario ?? 0;
    $detalle->cantidad_comprar = $request->cantidad_comprar ?? 0;
    $detalle->unidad_compra = $request->unidad_compra ?? $detalle->unidad;
    $detalle->save();
    
    $proveedoresGuardados = [];
    
    foreach ($request->proveedores as $proveedorData) {
        if (strpos($proveedorData['id'], 'nuevo-') !== false) {
            // Crear nuevo proveedor
            $proveedor = ProductoProveedor::create([
                'id' => GetUuid(),
                'detalle_id' => $detalle->id,
                'proveedor_id' => $proveedorData['proveedor_id'],
                'precio' => $proveedorData['precio'] ?? 0,
                'descuento' => $proveedorData['descuento'] ?? 0,
                'seleccionado' => $proveedorData['seleccionado'] ?? 0,
                'fecha_entrega' => $proveedorData['fecha_entrega'] ?? null,
            ]);
            $proveedoresGuardados[] = $proveedor;
        } else {
            // Actualizar existente
            $proveedor = ProductoProveedor::find($proveedorData['id']);
            if ($proveedor) {
                $proveedor->update([
                    'proveedor_id' => $proveedorData['proveedor_id'],
                    'precio' => $proveedorData['precio'] ?? 0,
                    'descuento' => $proveedorData['descuento'] ?? 0,
                    'seleccionado' => $proveedorData['seleccionado'] ?? 0,
                    'fecha_entrega' => $proveedorData['fecha_entrega'] ?? null,
                ]);
                $proveedoresGuardados[] = $proveedor;
            }
        }
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Item guardado correctamente',
        'data' => [
            'detalle' => $detalle,
            'proveedores' => $proveedoresGuardados
        ]
    ]);
}

    public function eliminarProveedorItem($id)
    {
        try {
            $proveedor = ProductoProveedor::findOrFail($id);
            $detalle = $proveedor->detalle;
            $requisicion = $detalle->requisicion;
            
            if ($requisicion->procesada == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede modificar una requisición procesada'
                ], 400);
            }
            
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
                ->with(['detalles.proveedores'])
                ->firstOrFail();
            
            $subtotal = 0;
            $descuento = 0;
            $iva = 0;
            $total = 0;
            
            foreach ($requisicion->detalles as $detalle) {
                $mejor = $detalle->proveedores->where('seleccionado', 1)->sortBy('precio')->first();
                if ($mejor) {
                    $subtotalItem = $detalle->cantidad * $mejor->precio;
                    $descuentoItem = ($subtotalItem * $mejor->descuento) / 100;
                    $subtotal += $subtotalItem;
                    $descuento += $descuentoItem;
                }
            }
            
            $iva = ($subtotal - $descuento) * 0.16;
            $total = ($subtotal - $descuento) + $iva;
            
            return response()->json([
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'iva' => $iva,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function eliminarRequisicion($id)
    {
        $requisicion = Requisicion::where('session_id', GetId())
            ->where('id', $id)
            ->firstOrFail();
        
        if ($requisicion->procesada == 1) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar una requisición procesada'
            ], 400);
        }
        
        $requisicion->delete();
        
        return response()->json(['success' => true]);
    }

    public function generarCompras($id)
{
    try {
        $sessionId = GetId();
        
        $requisicion = Requisicion::where('session_id', $sessionId)
            ->where('id', $id)
            ->with('detalles.proveedores')
            ->firstOrFail();

        if ($requisicion->procesada == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Esta requisición ya fue procesada'
            ], 400);
        }
        
        $items = $requisicion->detalles;
        
        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay items en esta requisición'
            ], 400);
        }
        
        $itemsSinProveedor = [];
        foreach ($items as $item) {
            $proveedorSel = $item->proveedores->where('seleccionado', 1)->first();
            if (!$proveedorSel) {
                $itemsSinProveedor[] = $item->clave;
            }
        }
        
        if (!empty($itemsSinProveedor)) {
            return response()->json([
                'success' => false,
                'message' => 'Los siguientes items no tienen proveedor seleccionado: ' . implode(', ', $itemsSinProveedor)
            ], 400);
        }
        
        $comprasPorProveedor = [];
        foreach ($items as $item) {
            $proveedorSel = $item->proveedores->where('seleccionado', 1)->first();
            $proveedorId = $proveedorSel->proveedor_id;
            
            if (!isset($comprasPorProveedor[$proveedorId])) {
                $comprasPorProveedor[$proveedorId] = [
                    'proveedor' => $proveedorSel->proveedor,
                    'items' => []
                ];
            }
            $comprasPorProveedor[$proveedorId]['items'][] = $item;
        }
        
        DB::beginTransaction();
        
        $totalCompras = 0;
        $contrato = $requisicion->contrato;
        $id_usuario = GetId();
        
        foreach ($comprasPorProveedor as $proveedorId => $data) {
            $itemsGrupo = $data['items'];
            $proveedor = $data['proveedor'];
            
            $subtotalTotal = 0;
            $descuentoTotal = 0;
            $ivaTotal = 0;
            $totalTotal = 0;
            
            foreach ($itemsGrupo as $item) {
                $proveedorSel = $item->proveedores->where('seleccionado', 1)->first();
                if ($proveedorSel) {
                    $cantidad = $item->cantidad_comprar ?? $item->cantidad;
                    $precioUnitario = $proveedorSel->precio;
                    $descuentoUnitario = $proveedorSel->descuento ?? 0;
                    $precioConDescuento = $precioUnitario - $descuentoUnitario;
                    
                    $subtotalItem = $cantidad * $precioConDescuento;
                    $descuentoItem = $cantidad * $descuentoUnitario;
                    $ivaItem = $subtotalItem * 0.16;
                    $totalItem = $subtotalItem + $ivaItem;
                    
                    $subtotalTotal += $subtotalItem;
                    $descuentoTotal += $descuentoItem;
                    $ivaTotal += $ivaItem;
                    $totalTotal += $totalItem;
                }
            }
            
            $compra = Compra::create([
                'id' => GetUuid(),
                'numeracion' => Compra::max('numeracion') + 1,
                'id_contrato' => $contrato->id ?? null,
                'id_usuario' => $id_usuario,
                'id_proveedor' => $proveedorId,
                'consecutivo' => $requisicion->consecutivo,
                'referencia' => 'Compra de requisición ' . $requisicion->consecutivo,
                'costo_operado' => $subtotalTotal,
                'iva' => $ivaTotal,
                'total' => $totalTotal,
                'metodo_pago' => null,
                'empresa_pago' => null,
                'verificado' => 1,
                'tipo_compra' => 'REQUISICION',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            foreach ($itemsGrupo as $index => $item) {
                $producto = ProductoServicio::where('clave', $item->clave)->first();
                $id_producto = $producto ? $producto->id : null;
                $proveedorSel = $item->proveedores->where('seleccionado', 1)->first();
                
                $cantidad = $item->cantidad_comprar ?? $item->cantidad;
                $unidad = $item->unidad_compra ?? $item->unidad;
                $partida = $item->partida ?? $requisicion->partida;
                $precioUnitario = $proveedorSel ? $proveedorSel->precio : 0;
                $descuentoUnitario = $proveedorSel ? $proveedorSel->descuento : 0;
                $porcentajeDescuento = ($descuentoUnitario / $precioUnitario) * 100;
                
                DB::table('compradetalle')->insert([
                    'id' => GetUuid(),
                    'id_compra' => $compra->id,
                    'id_productoservicio' => $id_producto,
                    'clave' => $item->clave,
                    'descripcion' => $item->descripcion,
                    'unidades' => $unidad,
                    'cantidad' => $cantidad,
                    'partida' => $partida,
                    'descuento_porcentaje' => $porcentajeDescuento ,
                    'descuento_monto' => $cantidad * $descuentoUnitario,
                    'ult_costo' => $precioUnitario,
                    'fecha_entrega' => $proveedorSel ? $proveedorSel->fecha_entrega : null,
                    'tipo_entrega' => null,
                    'comentarios' => null,
                    'orden' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            $totalCompras++;
        }
        
        $requisicion->procesada = 1;
        $requisicion->save();
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'total_compras' => $totalCompras
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error en generarCompras: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


    public function solicitudCotizacion($id)
    {   
        $requisicion = Requisicion::with(['contrato', 'detalles'])->findOrFail($id);
        
        $pdf = \PDF::loadView('acompras.requisiciones.solicitud_cotizacion_pdf', compact('requisicion'));
        $pdf->setPaper('letter', 'portrait');
        
        return $pdf->stream('Solicitud_Cotizacion_' . $requisicion->consecutivo . '.pdf');
    }
}