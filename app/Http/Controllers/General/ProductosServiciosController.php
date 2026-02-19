<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\ProductoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductosServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $productos = ProductoServicio::when($search, function($query, $search) {
                return $query->where('clave', 'LIKE', "%{$search}%")
                             ->orWhere('descripcion', 'LIKE', "%{$search}%");
            })
            ->orderBy('clave')
            ->paginate(12); // 12 items por página (3 columnas x 4 filas)
        
        return view('general.productosyservicios.index', compact('productos', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('general.productosyservicios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:32|unique:productosyservicios,clave',
            'descripcion' => 'required|string',
            'unidades' => 'required|string|max:10',
            'ult_costo' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            $producto = ProductoServicio::create([
                'id' => GetUuid(),
                'clave' => $request->clave,
                'descripcion' => $request->descripcion,
                'unidades' => $request->unidades,
                'ult_costo' => $request->ult_costo
            ]);

            DB::commit();

            return redirect()->route('productosyservicios.index')
                ->with('success', 'Producto/Servicio creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = ProductoServicio::find($id);
        
        if (!$producto) {
            return redirect()->route('productosyservicios.index')
                ->with('error', 'Producto/Servicio no encontrado');
        }

        return view('general.productosyservicios.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = ProductoServicio::find($id);
        
        if (!$producto) {
            return redirect()->route('productosyservicios.index')
                ->with('error', 'Producto/Servicio no encontrado');
        }

        return view('general.productosyservicios.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $producto = ProductoServicio::find($id);
        
        if (!$producto) {
            return redirect('productosyservicios/'.$id)
                ->with('error', 'Producto no encontrado');
        }

        $request->validate([
            'clave' => 'required|string|max:32|unique:productosyservicios,clave,' . $id . ',id',
            'descripcion' => 'required|string',
            'unidades' => 'required|string|max:10',
            'ult_costo' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            $producto->update([
                'clave' => $request->clave,
                'descripcion' => $request->descripcion,
                'unidades' => $request->unidades,
                'ult_costo' => $request->ult_costo
            ]);

            DB::commit();

            return redirect('productosyservicios/'.$id)
                ->with('success', 'Producto/Servicio actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el producto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = ProductoServicio::find($id);
        
        if (!$producto) {
            return redirect()->route('productosyservicios.index')
                ->with('error', 'Producto no encontrado');
        }

        try {
            DB::beginTransaction();
            
            // Verificar si el producto está siendo usado en otras tablas
            $enUso = DB::table('csdetalles')->where('id_productoservicio', $id)->exists();
            
            if ($enUso) {
                return redirect()->route('productosyservicios.index')
                    ->with('error', 'No se puede eliminar el producto porque está siendo utilizado en csdetalles');
            }

            $producto->delete();

            DB::commit();

            return redirect()->route('productosyservicios.index')
                ->with('success', 'Producto/Servicio eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('productosyservicios.index')
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }
}