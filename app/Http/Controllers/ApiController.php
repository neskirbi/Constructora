<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
class ApiController extends Controller
{
    public function GenerarPass(Request $request){
        try {
            $request->validate([
                'id' => 'required|string|size:32',
                'tipo' => 'required|string'
            ]);

            $userId = $request->id;
            $userType = $request->tipo;
            
            // Generar contraseña temporal de 8 caracteres
            $passTemp = GenerarPass();
            
            // Mapear tipos de usuario a tablas
            $tablas = [
                'administradores' => 'administradores', 
                'aingresos' => 'aingresos',
                'adestajos' => 'adestajos',
                'acompras' => 'acompras'
            ];
            
            $tabla = $tablas[$userType] ?? null;
            
            if (!$tabla) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Tipo de usuario no válido: ' . $userType
                ]);
            }

            // Actualizar la contraseña temporal
            $actualizado = DB::table($tabla)
                ->where('id', $userId)
                ->update(['passtemp' => $passTemp]);

            if ($actualizado) {
                return response()->json([
                    'status' => 1,
                    [
                        'passtemp' => $passTemp,
                        'id' => $userId
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Usuario no encontrado en la tabla: ' . $tabla
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }
}
