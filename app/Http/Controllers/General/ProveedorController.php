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
        
        return view('general.proveedores.create');
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
        
        return redirect()->back()->with('success', 'Proveedor creado exitosamente');
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


    public function guardarProveedor(Request $request)
    {
        try {
            // Validar los datos
            

            // Crear el proveedor usando el modelo ProveedorSer
            $proveedor = new ProveedorSer();
            $proveedor->id = GetUuid();
            $proveedor->clave = $request->clave;
            $proveedor->nombre = $request->nombre;
            $proveedor->calle = $request->calle;
            $proveedor->telefono = $request->telefono;
            $proveedor->clasificacion = $request->clasificacion;
            $proveedor->estatus = $request->estatus;
            $proveedor->especialidad = $request->especialidad;
            $proveedor->save();

            return response()->json([
                'success' => true,
                'message' => 'Proveedor guardado correctamente',
                'redirect' => route('proveedoresds.index')
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el proveedor: ' . $e->getMessage()
            ], 500);
        }
    }
}