<?php

namespace App\Http\Controllers\Acompras;

use App\Http\Controllers\Controller;
use App\Models\Requisicion;
use App\Models\Contrato;
use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\ProductoServicio;
use App\Models\RequisicionProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ProveedorSer;
use App\Services\GeminiExcelService;

class RequisicionController extends Controller
{
    public function index()
    {
        $sessionId = GetId();
        
        $requisiciones = Requisicion::where('session_id', $sessionId)
            ->with('contrato')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $resumen = [
            'total_items' => $requisiciones->count(),
            'subtotal' => $requisiciones->sum('subtotal'),
            'iva' => $requisiciones->sum('iva'),
            'total' => $requisiciones->sum('total'),
        ];
        
        $contratos = Contrato::orderBy('consecutivo', 'desc')
            ->select('id', 'consecutivo', 'contrato_no', 'refinterna', 'obra')
            ->limit(100)
            ->get();
        
        $proveedores = ProveedorSer::orderBy('clave')->get();
        
        return view('acompras.requisiciones.index', compact('requisiciones', 'resumen', 'contratos', 'proveedores'));
    }

    public function create()
    {
        $contratos = Contrato::orderBy('consecutivo', 'desc')
            ->select('id', 'consecutivo', 'contrato_no', 'refinterna', 'obra')
            ->limit(100)
            ->get();
        
        return view('acompras.requisiciones.create', compact('contratos'));
    }

    public function show($contratoId)
{
    $sessionId = GetId();
    
    $items = Requisicion::where('session_id', $sessionId)
        ->where('contrato_id', $contratoId)
        ->with('contrato')
        ->get();
    
    $contrato = Contrato::find($contratoId);
    $proveedores = ProveedorSer::orderBy('clave')->get();
    
    // Obtener proveedores de las requisiciones
    $requisicionIds = $items->pluck('id')->toArray();
    $requisicionProveedores = RequisicionProveedor::whereIn('requisicion_id', $requisicionIds)
        ->with('proveedor')
        ->get();
    
    return view('acompras.requisiciones.show', compact('items', 'contrato', 'contratoId', 'proveedores', 'requisicionProveedores'));
}

    public function borrarGrupo($contratoId)
    {
        Requisicion::where('session_id', GetId())
            ->where('contrato_id', $contratoId)
            ->delete();
        
        return response()->json(['success' => true]);
    }

    public function procesarExcel(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'contrato_id' => 'nullable|exists:contratos,id',
        ]);
        
        $sessionId = GetId();
        $contratoId = $request->contrato_id;
        $usarIA = $request->has('usar_ia') && $request->usar_ia == 1;
        
        // Si el usuario activó la IA, intentar con Gemini
        if ($usarIA) {
            try {
                $geminiService = new GeminiExcelService();
                $items = $geminiService->procesarExcel($request->file('archivo_excel'));
                
                if (!empty($items)) {
                    return $this->guardarItems($items, $sessionId, $contratoId, 'IA (Gemini)');
                }
            } catch (\Exception $e) {
                Log::error('Error con Gemini: ' . $e->getMessage());
                return redirect()->route('compras.requisiciones.index')
                    ->with('error', 'Error con IA: ' . $e->getMessage());
            }
        }
        
        // Si no usa IA o falló, método tradicional
        return $this->procesarExcelTradicional($request);
    }

    private function guardarItems($items, $sessionId, $contratoId, $metodo = 'tradicional')
    {
        $itemsAgregados = 0;
        $itemsErroneos = 0;
        $errores = [];
        
        foreach ($items as $item) {
            $clave = $item['clave'] ?? '';
            $descripcion = $item['descripcion'] ?? '';
            $unidad = $item['unidad'] ?? '';
            $cantidad = floatval($item['cantidad'] ?? 0);
            $link = $item['link'] ?? '';
            $observaciones = $item['observaciones'] ?? '';
            
            if ($clave == 'N/A' || empty($clave)) {
                $producto = ProductoServicio::where('descripcion', 'LIKE', '%' . $descripcion . '%')->first();
                if ($producto) {
                    $clave = $producto->clave;
                }
            }
            
            if (empty($clave) || empty($descripcion) || empty($unidad) || $cantidad <= 0) {
                $itemsErroneos++;
                $errores[] = "Datos incompletos: " . json_encode($item);
                continue;
            }
            
            $precio = 0;
            $catalogo = ProductoServicio::where('clave', $clave)->first();
            if ($catalogo) {
                $precio = $catalogo->ult_costo ?? 0;
            }
            
            $requisicion = Requisicion::create([
                'id' => GetUuid(),
                'session_id' => $sessionId,
                'contrato_id' => $contratoId,
                'clave' => $clave,
                'descripcion' => $descripcion,
                'unidad' => $unidad,
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
                'observaciones' => $observaciones,
                'link' => $link,
            ]);
            
            if ($precio > 0) {
                $requisicion->subtotal = $cantidad * $precio;
                $requisicion->iva = $requisicion->subtotal * 0.16;
                $requisicion->total = $requisicion->subtotal + $requisicion->iva;
                $requisicion->save();
            }
            
            $itemsAgregados++;
        }
        
        $mensaje = "Excel procesado con $metodo. Se agregaron $itemsAgregados items.";
        if ($itemsErroneos > 0) {
            $mensaje .= " $itemsErroneos items con errores.";
        }
        
        return redirect()->route('compras.requisiciones.index')
            ->with('success', $mensaje)
            ->with('itemsErroneos', $errores);
    }

    private function procesarExcelTradicional($request)
    {
        $sessionId = GetId();
        $contratoId = $request->contrato_id;
        $itemsAgregados = 0;
        $itemsErroneos = 0;
        $errores = [];
        
        try {
            $data = Excel::toArray([], $request->file('archivo_excel'));
            $rows = isset($data[1]) ? $data[1] : $data[0];
            
            // Limpiar filas vacías
            $rows = array_filter($rows, function($row) {
                foreach ($row as $celda) {
                    if (!empty(trim($celda ?? ''))) {
                        return true;
                    }
                }
                return false;
            });
            $rows = array_values($rows);
            
            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                $filaReal = $i + 1;
                
                $clave = trim($row[0] ?? '');
                $descripcion = trim($row[1] ?? '');
                $unidad = trim($row[2] ?? '');
                $cantidad = floatval(str_replace(',', '', $row[3] ?? 0));
                $link = trim($row[4] ?? '');
                $observaciones = trim($row[5] ?? '');
                
                if (empty($clave) && empty($descripcion)) {
                    continue;
                }
                
                if (is_numeric($clave) && strlen($clave) <= 2) {
                    continue;
                }
                
                if (empty($clave) || empty($descripcion) || empty($unidad) || $cantidad <= 0) {
                    $itemsErroneos++;
                    $errores[] = "Fila $filaReal: Datos incompletos (Clave: $clave)";
                    continue;
                }
                
                $precio = 0;
                $catalogo = ProductoServicio::where('clave', $clave)->first();
                if ($catalogo) {
                    $precio = $catalogo->ult_costo ?? 0;
                }
                
                $requisicion = Requisicion::create([
                    'id' => GetUuid(),
                    'session_id' => $sessionId,
                    'contrato_id' => $contratoId,
                    'clave' => $clave,
                    'descripcion' => $descripcion,
                    'unidad' => $unidad,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'observaciones' => $observaciones,
                    'link' => $link,
                    'fila_excel' => $filaReal,
                ]);
                
                if ($precio > 0) {
                    $requisicion->subtotal = $cantidad * $precio;
                    $requisicion->iva = $requisicion->subtotal * 0.16;
                    $requisicion->total = $requisicion->subtotal + $requisicion->iva;
                    $requisicion->save();
                }
                
                $itemsAgregados++;
            }
            
            $mensaje = "Excel procesado. Se agregaron $itemsAgregados items.";
            if ($itemsErroneos > 0) {
                $mensaje .= " $itemsErroneos items con errores.";
            }
            
            return redirect()->route('compras.requisiciones.index')
                ->with('success', $mensaje)
                ->with('itemsErroneos', $errores);
                
        } catch (\Exception $e) {
            return redirect()->route('compras.requisiciones.index')
                ->with('error', 'Error: ' . $e->getMessage());
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
    
    public function confirmarCompraPorContrato(Request $request, $contratoId)
    {
        $sessionId = GetId();
        $items = Requisicion::where('session_id', $sessionId)
            ->where('contrato_id', $contratoId)
            ->get();
        
        if ($items->isEmpty()) {
            return redirect()->route('compras.requisiciones.index')
                ->with('error', 'No hay items para este contrato.');
        }
        
        $sinPrecio = $items->filter(function($item) {
            return empty($item->precio_unitario) || $item->precio_unitario == 0;
        });
        
        if ($sinPrecio->count() > 0) {
            $claves = $sinPrecio->pluck('clave')->implode(', ');
            return redirect()->route('compras.requisiciones.index')
                ->with('error', "Items sin precio: {$claves}");
        }
        
        $contrato = Contrato::find($contratoId);
        
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
                'id_contrato' => $contrato->id,
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
                $producto = ProductoServicio::where('clave', $item->clave)->first();
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
            
            Requisicion::where('session_id', $sessionId)
                ->where('contrato_id', $contratoId)
                ->delete();
            
            DB::commit();
            
            return redirect()->route('compras.show', $compra->id)
                ->with('success', "Compra #{$compra->numeracion} creada exitosamente.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear compra: ' . $e->getMessage());
            return redirect()->route('compras.requisiciones.index')
                ->with('error', 'Error al crear la compra: ' . $e->getMessage());
        }
    }
    


    public function agregarProveedor(Request $request)
{
    $request->validate([
    'requisicion_id' => 'required|exists:requisiciones,id',
    'proveedor_id' => 'required|exists:proveedores_servicios,id', // <--- Cambia aquí
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
}