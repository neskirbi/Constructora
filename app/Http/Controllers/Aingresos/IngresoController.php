<?php

namespace App\Http\Controllers\Aingresos;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Obtener parámetros de búsqueda
        $search = $request->input('search');
        
        // Construir consulta base
        $query = Contrato::query();
        
        // Aplicar búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('obra', 'like', "%{$search}%")
                  ->orWhere('contrato_no', 'like', "%{$search}%")
                  ->orWhere('cliente', 'like', "%{$search}%")
                  ->orWhere('lugar', 'like', "%{$search}%")
                  ->orWhere('empresa', 'like', "%{$search}%");
            });
        }
        
      
        
        // Obtener los contratos con paginación (15 por página)
        $contratos = $query->orderBy('created_at', 'desc')->paginate();
        
        return view('aingresos.ingresos.index', compact('contratos'));
    }
}