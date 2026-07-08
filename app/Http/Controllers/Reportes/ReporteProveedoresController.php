<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProveedorSer;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProveedoresExport;

class ReporteProveedoresController extends Controller
{
    public function index()
    {
        return view('reportes.proveedores.proveedores');
    }

    public function exportar(Request $request)
    {
        $request->validate([
            'filtro' => 'required|in:todos,rango_clave,busqueda,estatus',
            'clave_desde' => 'required_if:filtro,rango_clave|nullable|numeric',
            'clave_hasta' => 'required_if:filtro,rango_clave|nullable|numeric',
            'buscar_texto' => 'required_if:filtro,busqueda|nullable|string|max:255',
            'estatus' => 'required_if:filtro,estatus|nullable|string|in:Activo,Inactivo',
        ]);

        $query = ProveedorSer::query();

        switch ($request->filtro) {
            case 'rango_clave':
                $query->whereRaw('CAST(clave AS DECIMAL(10,2)) >= ?', [$request->clave_desde])
                      ->whereRaw('CAST(clave AS DECIMAL(10,2)) <= ?', [$request->clave_hasta]);
                break;
            case 'busqueda':
                $query->where(function($q) use ($request) {
                    $q->where('clave', 'LIKE', '%' . $request->buscar_texto . '%')
                      ->orWhere('nombre', 'LIKE', '%' . $request->buscar_texto . '%');
                });
                break;
            case 'estatus':
                $query->where('estatus', $request->estatus);
                break;
            case 'todos':
            default:
                break;
        }

        $proveedores = $query->orderByRaw('CAST(clave AS DECIMAL(10,2)) ASC')->get();

        if ($proveedores->isEmpty()) {
            return back()->with('error', 'No se encontraron proveedores con los filtros seleccionados.');
        }

        return Excel::download(new ProveedoresExport($proveedores), 'proveedores_' . date('Y-m-d_His') . '.xlsx');
    }
}