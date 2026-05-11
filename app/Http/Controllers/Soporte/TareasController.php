<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contrato;

class TareasController extends Controller
{
    function SacarTotales(){
        $contratos = Contrato::all();

        foreach($contratos as $contrato){
            TotalContrato($contrato->id);
            TotalIngresos($contrato->id);
            TotalLiquido($contrato->id);
        }
         

        return 'ok';
    }
}
