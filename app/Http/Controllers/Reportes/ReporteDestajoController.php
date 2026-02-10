<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Destajo;
use App\Models\Contrato;
use App\Models\ProveedoresServicio;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DestajosExport;

class ReporteDestajoController extends Controller
{
    public function index()
    {
        $contratos = Contrato::select('id', 'refinterna', 'obra')
            ->orderBy('refinterna')
            ->get();
        
        return view('reportes.destajo.destajo', compact('contratos'));
    }

    public function exportar(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'contrato_id' => 'nullable|exists:contratos,id'
        ]);

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $contratoId = $request->contrato_id;

        return Excel::download(
            new DestajosExport($fechaInicio, $fechaFin, $contratoId), 
            'reporte_destajos_' . date('Y-m-d') . '.xlsx'
        );
    }
}