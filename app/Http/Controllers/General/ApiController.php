<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProveedorSer;
use App\Models\ProductoServicio;

class ApiController extends Controller
{
    public function BuscarProveedor(Request $request)
    {
        $search = $request->get('q', '');
        $limit = 15; // Solo 5 resultadosfgh
        
        $query = ProveedorSer::query();
        
        // Filtrar solo activos
        $query->where('estatus', 'Activo');
        
        // Buscar SOLO por clave
        if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('clave', 'LIKE', "%{$search}%")
              ->orWhere('nombre', 'LIKE', "%{$search}%");
        });
    }
        
        $proveedores = $query->orderBy('clave')
                             ->limit($limit)
                             ->get();
        
        $results = [];
        foreach ($proveedores as $proveedor) {
            $results[] = [
                'id' => $proveedor->id,
                'clave' => $proveedor->clave,
                'nombre' => $proveedor->nombre,
                'especialidad' => $proveedor->especialidad,
                'telefono' => $proveedor->telefono,
                'calle' => $proveedor->calle
            ];
        }
        
        return response()->json($results);
    }

    public function BuscarProductos(Request $request)
    {
        $search = $request->get('q', '');
        $limit = 30;
        
        $query = ProductoServicio::query();
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('clave', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }
        
        $productos = $query->select('id', 'clave', 'descripcion', 'unidades', 'ult_costo')
                           ->orderBy('clave')
                           ->limit($limit)
                           ->get();
        
        return response()->json($productos);
    }
}