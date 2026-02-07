<?php

namespace App\Http\Controllers\Adestajos;

use App\Models\ProveedorSer;
use Illuminate\Http\Request;
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
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('adestajos.proveedores.index', compact('proveedores', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $estatusOptions = ['Activo', 'Inactivo', 'Suspendido'];
        $especialidadOptions = [
            'Cimentación',
            'Estructuras',
            'Albañilería',
            'Aplanados',
            'Yeso',
            'Tablaroca',
            'Pintura',
            'Impermeabilización',
            'Azulejo',
            'Mármol y Granito',
            'Carpintería',
            'Herrería',
            'Cristalería',
            'Plomería',
            'Electricidad',
            'Instalaciones Sanitarias',
            'Instalaciones Hidráulicas',
            'Instalaciones Eléctricas',
            'Instalaciones de Gas',
            'Aire Acondicionado',
            'Ventilación',
            'Instalaciones Especiales',
            'Acabados',
            'Pisos y Recubrimientos',
            'Fachadas',
            'Techados',
            'Demoliciones',
            'Excavaciones',
            'Movimientos de Tierra',
            'Compactación',
            'Andamios',
            'Encofrados',
            'Cimbras',
            'Instalación de Cancelaría',
            'Jardinería',
            'Urbanización',
            'Pavimentos',
            'Drenajes',
            'Instalaciones Deportivas',
            'Otros'
        ];
        
        return view('adestajos.proveedores.create', compact(
            'estatusOptions', 
            'especialidadOptions'
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
        
        $estatusOptions = ['Activo', 'Inactivo', 'Suspendido'];
        $especialidadOptions = [
            'Cimentación',
            'Estructuras',
            'Albañilería',
            'Aplanados',
            'Yeso',
            'Tablaroca',
            'Pintura',
            'Impermeabilización',
            'Azulejo',
            'Mármol y Granito',
            'Carpintería',
            'Herrería',
            'Cristalería',
            'Plomería',
            'Electricidad',
            'Instalaciones Sanitarias',
            'Instalaciones Hidráulicas',
            'Instalaciones Eléctricas',
            'Instalaciones de Gas',
            'Aire Acondicionado',
            'Ventilación',
            'Instalaciones Especiales',
            'Acabados',
            'Pisos y Recubrimientos',
            'Fachadas',
            'Techados',
            'Demoliciones',
            'Excavaciones',
            'Movimientos de Tierra',
            'Compactación',
            'Andamios',
            'Encofrados',
            'Cimbras',
            'Instalación de Cancelaría',
            'Jardinería',
            'Urbanización',
            'Pavimentos',
            'Drenajes',
            'Instalaciones Deportivas',
            'Otros'
        ];
        
        return view('adestajos.proveedores.show', compact(
            'estatusOptions', 
            'especialidadOptions',
            'proveedor'
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
        
        return redirect()->route('proveedoresds.index')
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