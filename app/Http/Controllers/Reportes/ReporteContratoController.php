<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContratosExport;

class ReporteContratoController extends Controller
{
    public function index()
    {
        $contratos = Contrato::select('id', 'refinterna', 'obra', 'contrato_no')
            ->orderBy('refinterna')
            ->get();
        
        return view('reportes.contratos.contrato', compact('contratos'));
    }

    public function generar(Request $request)
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
            new ContratosExport($fechaInicio, $fechaFin, $contratoId), 
            'reporte_contratos_' . date('Y-m-d') . '.xlsx'
        );
    }
}