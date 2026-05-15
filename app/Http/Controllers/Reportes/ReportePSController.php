<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductoServicio;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosServiciosExport;

class ReportePSController extends Controller
{
    public function index()
    {
        return view('reportes.ps.ps');
    }

    public function exportar(Request $request)
    {
        $request->validate([
            'filtro' => 'required|in:todos,clave_unica,rango_clave,busqueda',
            'clave_unica' => 'required_if:filtro,clave_unica|nullable|string|max:32',
            'clave_desde' => 'required_if:filtro,rango_clave|nullable|string|max:32',
            'clave_hasta' => 'required_if:filtro,rango_clave|nullable|string|max:32',
            'buscar_texto' => 'required_if:filtro,busqueda|nullable|string|max:255',
        ]);

        $query = ProductoServicio::query();

        switch ($request->filtro) {
            case 'clave_unica':
                $query->where('clave', $request->clave_unica);
                break;
            case 'rango_clave':
                $query->whereBetween('clave', [$request->clave_desde, $request->clave_hasta]);
                break;
            case 'busqueda':
                $query->where(function($q) use ($request) {
                    $q->where('clave', 'LIKE', '%' . $request->buscar_texto . '%')
                      ->orWhere('descripcion', 'LIKE', '%' . $request->buscar_texto . '%');
                });
                break;
            case 'todos':
            default:
                break;
        }

        $productos = $query->orderBy('clave', 'asc')->get();

        if ($productos->isEmpty()) {
            return back()->with('error', 'No se encontraron productos/servicios con los filtros seleccionados.');
        }

        return Excel::download(new ProductosServiciosExport($productos), 'productos_servicios_' . date('Y-m-d_His') . '.xlsx');
    }
}