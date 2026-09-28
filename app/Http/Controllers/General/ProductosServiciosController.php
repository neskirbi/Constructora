<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\ProductoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductosServiciosController extends Controller
{
    /**
     * Carpeta base (dentro de public/) donde se guardan los PDF.
     */
    private function carpetaPdf(): string
    {
        return public_path('pdf/productosyservicios');
    }

    /**
     * Guarda un PDF en public/pdf/productosyservicios y regresa
     * la ruta relativa (ej: pdf/productosyservicios/archivo.pdf).
     */
    private function guardarPdf($archivo): ?string
    {
        if (!$archivo) {
            return null;
        }

        $carpeta = $this->carpetaPdf();

        // Crea la carpeta si no existe
        if (!File::exists($carpeta)) {
            File::makeDirectory($carpeta, 0755, true, true);
        }

        // Nombre único para no pisar archivos
        $nombre = uniqid('pdf_', true) . '_' . time() . '.' . $archivo->getClientOriginalExtension();

        // Mueve el archivo a public/pdf/productosyservicios
        $archivo->move($carpeta, $nombre);

        // Regresa la ruta relativa (para guardar en BD)
        return 'pdf/productosyservicios/' . $nombre;
    }

    /**
     * Borra un PDF físico si existe.
     */
    private function borrarPdf(?string $ruta): void
    {
        if ($ruta && File::exists(public_path($ruta))) {
            File::delete(public_path($ruta));
        }
    }

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
            ->paginate(12);
        
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
            'precio' => 'nullable|numeric|min:0',
            'archivo_pdf_1' => 'nullable|file|mimes:pdf|max:10240',
            'archivo_pdf_2' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        try {
            DB::beginTransaction();

            // Guardar PDFs (si vienen)
            $rutaPdf1 = $this->guardarPdf($request->file('archivo_pdf_1'));
            $rutaPdf2 = $this->guardarPdf($request->file('archivo_pdf_2'));
            
            $producto = ProductoServicio::create([
                'id' => GetUuid(),
                'clave' => $request->clave,
                'descripcion' => $request->descripcion,
                'unidades' => $request->unidades,
                'ult_costo' => 0.0,
                'precio' => $request->precio ?? 0.0,
                'archivo_pdf_1' => $rutaPdf1,
                'archivo_pdf_2' => $rutaPdf2,
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

        try {
            DB::beginTransaction();

            // Datos base
            $datos = [
                'clave' => $request->clave,
                'descripcion' => $request->descripcion,
                'unidades' => $request->unidades,
                'precio' => $request->precio ?? $producto->precio,
            ];

            // Si suben nuevo PDF 1, borrar viejo y guardar nuevo
            if ($request->hasFile('archivo_pdf_1')) {
                $this->borrarPdf($producto->archivo_pdf_1);
                $datos['archivo_pdf_1'] = $this->guardarPdf($request->file('archivo_pdf_1'));
            }

            // Si suben nuevo PDF 2, borrar viejo y guardar nuevo
            if ($request->hasFile('archivo_pdf_2')) {
                $this->borrarPdf($producto->archivo_pdf_2);
                $datos['archivo_pdf_2'] = $this->guardarPdf($request->file('archivo_pdf_2'));
            }
            
            $producto->update($datos);

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
            $enUso = DB::table('destajodetalles')->where('id_productoservicio', $id)->exists();
            
            if ($enUso) {
                return redirect()->route('productosyservicios.index')
                    ->with('error', 'No se puede eliminar el producto porque está siendo utilizado en csdetalles');
            }

            // Borrar PDFs físicos antes de eliminar el registro
            $this->borrarPdf($producto->archivo_pdf_1);
            $this->borrarPdf($producto->archivo_pdf_2);

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


    /**
     * Api para guardar Productos y servicios
     */
    function NuevoPS(Request $request)
    {
        try {
            $request->validate([
                'clave' => 'required|string|max:32|unique:productosyservicios,clave',
                'descripcion' => 'required|string',
                'unidades' => 'required|string|max:10',
                'precio' => 'nullable|numeric|min:0',
                'archivo_pdf_1' => 'nullable|file|mimes:pdf|max:10240',
                'archivo_pdf_2' => 'nullable|file|mimes:pdf|max:10240',
            ]);
            
            $id = GetUuid();

            // Guardar PDFs (si vienen)
            $rutaPdf1 = $this->guardarPdf($request->file('archivo_pdf_1'));
            $rutaPdf2 = $this->guardarPdf($request->file('archivo_pdf_2'));
            
            DB::table('productosyservicios')->insert([
                'id' => $id,
                'clave' => $request->clave,
                'descripcion' => $request->descripcion,
                'unidades' => $request->unidades,
                'ult_costo' => 0,
                'precio' => $request->precio ?? 0,
                'archivo_pdf_1' => $rutaPdf1,
                'archivo_pdf_2' => $rutaPdf2,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $producto = DB::table('productosyservicios')->where('id', $id)->first();
            
            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente',
                'producto' => $producto
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el producto: ' . $e->getMessage()
            ], 500);
        }
    }
}