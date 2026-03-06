<?php

namespace App\Http\Controllers\General;

use App\Models\ProveedorSer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $proveedores = ProveedorSer::query()
            ->when($search, function ($query, $search) {
                return $query->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('clave', 'like', '%' . $search . '%')
                    ->orWhere('telefono', 'like', '%' . $search . '%')
                    ->orWhere('clasificacion', 'like', '%' . $search . '%')
                    ->orWhere('especialidad', 'like', '%' . $search . '%');
            })
            ->orderBy('nombre', 'asc')
            ->paginate(15);
        
        return view('general.proveedores.index', compact('proveedores', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener la clave más grande (como es varchar, convertir a número)
        $maxClave = DB::table('proveedores_servicios')
            ->select(DB::raw('MAX(CAST(clave AS UNSIGNED)) as max_clave'))
            ->value('max_clave');
        
        $siguienteClave = $maxClave ? $maxClave + 1 : 1;
        
        // Obtener especialidades únicas de la base de datos
        $especialidadOptions = DB::table('proveedores_servicios')
            ->select('especialidad')
            ->distinct()
            ->whereNotNull('especialidad')
            ->where('especialidad', '!=', '')
            ->orderBy('especialidad')
            ->pluck('especialidad')
            ->toArray();
        
        $estatusOptions = ['Activo', 'Inactivo', 'Suspendido'];
        
        $clasificacionOptions = ['ADMON', 'COMPRAS', 'DESTAJO', 'MATERIALES', 'SERVICIOS'];
        
        return view('general.proveedores.create', compact(
            'siguienteClave',
            'estatusOptions', 
            'especialidadOptions',
            'clasificacionOptions'
        ));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:50|unique:proveedores_servicios,clave',
            'estatus' => 'required|string|max:50',
            'nombre' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'clasificacion' => 'required|string|max:100',
            'especialidad' => 'required|string|max:255',
        ]);
        
        $proveedor = new ProveedorSer();
        $proveedor->id = GetUuid();
        $proveedor->clave = $request->clave;
        $proveedor->estatus = $request->estatus;
        $proveedor->nombre = $request->nombre;
        $proveedor->calle = $request->calle;
        $proveedor->telefono = $request->telefono;
        $proveedor->clasificacion = $request->clasificacion;
        $proveedor->especialidad = $request->especialidad;
        $proveedor->save();
        
        return redirect()->route('proveedoresds.index')
            ->with('success', 'Proveedor creado exitosamente');
    }

    public function show($id)
{
    $proveedor = ProveedorSer::findOrFail($id);
    
    // Obtener especialidades únicas de la base de datos
    $especialidadOptions = DB::table('proveedores_servicios')
        ->select('especialidad')
        ->distinct()
        ->whereNotNull('especialidad')
        ->where('especialidad', '!=', '')
        ->orderBy('especialidad')
        ->pluck('especialidad')
        ->toArray();
    
    $estatusOptions = ['Activo', 'Inactivo', 'Suspendido'];
    
    $clasificacionOptions = ['ADMON', 'COMPRAS', 'DESTAJO', 'MATERIALES', 'SERVICIOS'];
    
    return view('general.proveedores.show', compact(
        'proveedor',
        'estatusOptions', 
        'especialidadOptions',
        'clasificacionOptions'
    ));
}

    public function update(Request $request, $id)
    {
        $proveedor = ProveedorSer::findOrFail($id);
        
        $request->validate([
            'clave' => 'required|string|max:50|unique:proveedores_servicios,clave,' . $proveedor->id,
            'estatus' => 'required|string|max:50',
            'nombre' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'clasificacion' => 'required|string|max:100',
            'especialidad' => 'required|string|max:255',
        ]);
        
        $proveedor->clave = $request->clave;
        $proveedor->estatus = $request->estatus;
        $proveedor->nombre = $request->nombre;
        $proveedor->calle = $request->calle;
        $proveedor->telefono = $request->telefono;
        $proveedor->clasificacion = $request->clasificacion;
        $proveedor->especialidad = $request->especialidad;
        
        $proveedor->save();
        
        return redirect('proveedoresds/'.$proveedor->id)
            ->with('success', 'Proveedor actualizado exitosamente');
    }

    public function destroy($id)
    {
        $proveedor = ProveedorSer::findOrFail($id);
        $proveedor->delete();
        
        return redirect()->route('proveedoresds.index')
            ->with('success', 'Proveedor eliminado exitosamente');
    }
}