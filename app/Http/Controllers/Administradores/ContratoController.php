<?php

namespace App\Http\Controllers\Administradores;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\Models\Contrato;
use Illuminate\Http\Request;

class ContratoController extends Controller
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
        
        return view('administradores.contratos.index', compact('contratos'));
    }

    function create(){
        return view('administradores.contratos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Buscar el contrato
            $contrato = Contrato::findOrFail($id);
            
            // Cargar la vista de edición (reutilizamos el formulario CREATE)
            return view('administradores.contratos.show', compact('contrato'));
            
        } catch (\Exception $e) {
            // Si no se encuentra el contrato
            return redirect()->route('acontratos.index')
                ->with('error', 'Contrato no encontrado: ' . $e->getMessage());
        }
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
        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Buscar el contrato
            $contrato = Contrato::findOrFail($id);
            
            // Verificar si existen registros en la tabla ingresos relacionados con este contrato
            $tieneIngresos = DB::table('ingresos')
                ->where('id_contrato', $id)
                ->exists();
            
            if ($tieneIngresos) {
                // Si hay ingresos relacionados, no se puede eliminar
                return redirect()->route('acontratos.index')
                    ->with('error', 'No se puede eliminar el contrato "' . $contrato->contrato_no . 
                        '" porque tiene registros de ingresos asociados. ' .
                        'Elimine primero los ingresos relacionados.');
            }
            
            // Guardar información para el mensaje
            $contratoNo = $contrato->contrato_no;
            $obraNombre = $contrato->obra;
            
            // Eliminar el contrato
            $contrato->delete();
            
            // Redireccionar con mensaje de éxito
            return redirect()->route('acontratos.index')
                ->with('success', 'Contrato "' . $contratoNo . '" - "' . $obraNombre . '" eliminado exitosamente.');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si no se encuentra el contrato
            return redirect()->route('acontratos.index')
                ->with('error', 'El contrato no existe o ya fue eliminado.');
                
        } catch (\Exception $e) {
            // Para cualquier otro error
            \Log::error('Error al eliminar contrato ID ' . $id . ': ' . $e->getMessage());
            
            return redirect()->route('acontratos.index')
                ->with('error', 'Error al eliminar el contrato: ' . $e->getMessage());
        }
    }
}