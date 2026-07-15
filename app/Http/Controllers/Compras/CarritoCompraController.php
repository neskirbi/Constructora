<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use App\Models\CarritoCompra;
use App\Models\Contrato;
use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\ProductoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ComprasExcelImport;

class CarritoCompraController extends Controller
{
    public function index()
    {
        $sessionId = session()->getId();
        
        $carrito = CarritoCompra::where('session_id', $sessionId)
            ->with('contrato')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $resumen = [
            'total_items' => $carrito->count(),
            'subtotal' => $carrito->sum('subtotal'),
            'iva' => $carrito->sum('iva'),
            'total' => $carrito->sum('total'),
        ];
        
        $contratos = Contrato::orderBy('consecutivo', 'desc')
            ->select('id', 'consecutivo', 'contrato_no', 'refinterna', 'obra')
            ->limit(100)
            ->get();
        
        return view('acompras.compras.carrito.index', compact('carrito', 'resumen', 'contratos'));
    }
    
    public function procesarExcel(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'contrato_id' => 'nullable|exists:contratos,id',
        ]);
        
        $sessionId = GetId();
        $contratoId = $request->contrato_id;
        $itemsAgregados = 0;
        $itemsErroneos = 0;
        $errores = [];
        
        try {
            $data = Excel::toArray([], $request->file('archivo_excel'));
            $rows = isset($data[1]) ? $data[1] : $data[0];
            
            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                $filaReal = $i + 1;
                
                // Hoja1: Columna A = Clave, B = Descripción, C = Unidad, D = Cantidad, E = Link, F = Observaciones
                $clave = trim($row[0] ?? '');
                $descripcion = trim($row[1] ?? '');
                $unidad = trim($row[2] ?? '');
                $cantidad = floatval(str_replace(',', '', $row[3] ?? 0));
                $link = trim($row[4] ?? '');
                $observaciones = trim($row[5] ?? '');
                
                // Saltar filas vacías
                if (empty($clave) && empty($descripcion)) {
                    continue;
                }
                
                // Si la clave es un número (1, 2, 3...), saltar esa fila
                if (is_numeric($clave) && strlen($clave) <= 2) {
                    continue;
                }
                
                if (empty($clave) || empty($descripcion) || empty($unidad) || $cantidad <= 0) {
                    $itemsErroneos++;
                    $errores[] = "Fila $filaReal: Datos incompletos (Clave: $clave)";
                    continue;
                }
                
                // Buscar en catálogo
                $precio = 0;
                $catalogo = ProductoServicio::where('clave', $clave)->first();
                if ($catalogo) {
                    $precio = $catalogo->ult_costo ?? 0;
                }
                
                // Crear item
                $item = CarritoCompra::create([
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
                    $item->subtotal = $cantidad * $precio;
                    $item->iva = $item->subtotal * 0.16;
                    $item->total = $item->subtotal + $item->iva;
                    $item->save();
                }
                
                $itemsAgregados++;
            }
            
            $mensaje = "Excel procesado. Se agregaron $itemsAgregados items.";
            if ($itemsErroneos > 0) {
                $mensaje .= " $itemsErroneos items con errores.";
            }
            
            return redirect()->route('compras.carrito.index')
                ->with('success', $mensaje)
                ->with('itemsErroneos', $errores);
                
        } catch (\Exception $e) {
            return redirect()->route('compras.carrito.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function asignarContrato(Request $request)
    {
        $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
        ]);
        
        CarritoCompra::where('session_id', session()->getId())
            ->update(['contrato_id' => $request->contrato_id]);
        
        return redirect()->route('compras.carrito.index')
            ->with('success', 'Contrato asignado a todos los items del carrito.');
    }
    
    public function actualizarPrecio(Request $request, $id)
    {
        $request->validate([
            'precio_unitario' => 'required|numeric|min:0',
        ]);
        
        $item = CarritoCompra::where('session_id', session()->getId())
            ->where('id', $id)
            ->firstOrFail();
        
        $item->precio_unitario = $request->precio_unitario;
        $item->subtotal = $item->cantidad * $item->precio_unitario;
        $item->iva = $item->subtotal * 0.16;
        $item->total = $item->subtotal + $item->iva;
        $item->save();
        
        return redirect()->route('compras.carrito.index')
            ->with('success', 'Precio actualizado correctamente.');
    }
    
    public function buscarPreciosCatalogo()
    {
        $sessionId = session()->getId();
        
        $items = CarritoCompra::where('session_id', $sessionId)
            ->where(function($q) {
                $q->whereNull('precio_unitario')->orWhere('precio_unitario', 0);
            })
            ->get();
        
        $actualizados = 0;
        
        foreach ($items as $item) {
            $catalogo = ProductoServicio::where('clave', $item->clave)->first();
            
            if ($catalogo && $catalogo->ult_costo > 0) {
                $item->precio_unitario = $catalogo->ult_costo;
                $item->subtotal = $item->cantidad * $item->precio_unitario;
                $item->iva = $item->subtotal * 0.16;
                $item->total = $item->subtotal + $item->iva;
                $item->save();
                $actualizados++;
            }
        }
        
        return redirect()->route('compras.carrito.index')
            ->with('success', "Se actualizaron {$actualizados} items con precios del catálogo.");
    }
    
    public function eliminarItem($id)
    {
        $item = CarritoCompra::where('session_id', session()->getId())
            ->where('id', $id)
            ->firstOrFail();
        
        $item->delete();
        
        return redirect()->route('compras.carrito.index')
            ->with('success', 'Item eliminado del carrito.');
    }
    
    public function vaciarCarrito()
    {
        CarritoCompra::where('session_id', session()->getId())->delete();
        
        return redirect()->route('compras.carrito.index')
            ->with('success', 'Carrito vaciado correctamente.');
    }
    
    public function confirmarCompra(Request $request)
    {
        $sessionId = session()->getId();
        $items = CarritoCompra::where('session_id', $sessionId)->get();
        
        if ($items->isEmpty()) {
            return redirect()->route('compras.carrito.index')
                ->with('error', 'El carrito está vacío.');
        }
        
        $sinPrecio = $items->filter(function($item) {
            return empty($item->precio_unitario) || $item->precio_unitario == 0;
        });
        
        if ($sinPrecio->count() > 0) {
            $claves = $sinPrecio->pluck('clave')->implode(', ');
            return redirect()->route('compras.carrito.index')
                ->with('error', "Los siguientes items no tienen precio: {$claves}");
        }
        
        $sinContrato = $items->filter(function($item) {
            return empty($item->contrato_id);
        });
        
        if ($sinContrato->count() > 0) {
            return redirect()->route('compras.carrito.index')
                ->with('error', 'Todos los items deben tener un contrato asignado.');
        }
        
        $contrato = Contrato::find($items->first()->contrato_id);
        
        try {
            DB::beginTransaction();
            
            $compra = Compra::create([
                'consecutivo' => Compra::where('empresa', $contrato->empresa)->max('consecutivo') + 1,
                'empresa' => $contrato->empresa,
                'contrato_id' => $contrato->id,
                'fecha_compra' => now(),
                'proveedor_nombre' => $request->proveedor_nombre ?? 'Por definir',
                'verificado' => 1,
                'costo_operado' => $items->sum('subtotal'),
                'iva' => $items->sum('iva'),
                'total' => $items->sum('total'),
                'referencia' => $request->referencia ?? null,
                'observaciones' => $request->observaciones ?? null,
            ]);
            
            foreach ($items as $item) {
                CompraDetalle::create([
                    'compra_id' => $compra->id,
                    'clave' => $item->clave,
                    'descripcion' => $item->descripcion,
                    'unidades' => $item->unidad,
                    'cantidad' => $item->cantidad,
                    'ult_costo' => $item->precio_unitario,
                    'subtotal' => $item->subtotal,
                    'iva' => $item->iva,
                    'total' => $item->total,
                ]);
            }
            
            CarritoCompra::where('session_id', $sessionId)->delete();
            
            DB::commit();
            
            return redirect()->route('compras.show', $compra->id)
                ->with('success', "Compra #{$compra->consecutivo} creada exitosamente.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear compra desde carrito: ' . $e->getMessage());
            return redirect()->route('compras.carrito.index')
                ->with('error', 'Error al crear la compra: ' . $e->getMessage());
        }
    }
}