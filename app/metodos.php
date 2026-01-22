<?php

use App\Models\Administrador;
use App\Models\Aingreso;
use App\Models\Adestajo;
use App\Models\Acompra;

date_default_timezone_set('America/Mexico_City');


function Memoria(){
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0); 
    ini_set('post_max_size', '30G');
}

function Version(){
    return 1;
}

function GenerarPass(){  
    return \Illuminate\Support\Str::random(8);
}


/**
 * Path Fotos
 */
function ProyectName(){
    return '';
}

function Empresa(){
    return 'Constructora';
}


function GetUuid(){
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
    return str_replace("-","",vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
}


    function ValidarMail($mail)
    {
        // Administradores Generales
        if ($administrador = Administrador::where('mail', $mail)->first()) {
            return ['existe' => true, 'tabla' => 'administradores', 'modelo' => $administrador];
        }

        // Administradores de Ingresos
        if ($aingreso = Aingreso::where('mail', $mail)->first()) {
            return ['existe' => true, 'tabla' => 'aingresos', 'modelo' => $aingreso];
        }

        // Administradores de Destajos
        if ($adestajo = Adestajo::where('mail', $mail)->first()) {
            return ['existe' => true, 'tabla' => 'adestajos', 'modelo' => $adestajo];
        }

        // Administradores de Compras
        if ($acompra = Acompra::where('mail', $mail)->first()) {
            return ['existe' => true, 'tabla' => 'acompras', 'modelo' => $acompra];
        }

        return ['existe' => false, 'tabla' => '', 'modelo' => null];
    }


    function GetNombre() {
        // Intenta con cada guard en orden de prioridad
        if (Auth::guard('administradores')->check()) {
            $user = Auth::guard('administradores')->user();
            return trim($user->nombres . ' ' . $user->apellidos);
        }
        
        if (Auth::guard('aingresos')->check()) {
            $user = Auth::guard('aingresos')->user();
            return trim($user->nombres . ' ' . $user->apellidos);
        }
        
        if (Auth::guard('adestajos')->check()) {
            $user = Auth::guard('adestajos')->user();
            return trim($user->nombres . ' ' . $user->apellidos);
        }
        
        if (Auth::guard('acompras')->check()) {
            $user = Auth::guard('acompras')->user();
            return trim($user->nombres . ' ' . $user->apellidos);
        }
        
        return 'Invitado';
    }

 
    function GetRol()
    {
        // Verificar cada guard en orden según tu config/auth.php
        
        // 1. Administradores (guard por defecto)
        if (Auth::guard('administradores')->check()) {
            $user = Auth::guard('administradores')->user();
            return ($user->principal == 1) ? 'Administrador Principal' : 'Administrador General';
        }
        
        // 2. Administrador de Ingresos
        if (Auth::guard('aingresos')->check()) {
            return 'Administrador de Ingresos';
        }
        
        // 3. Administrador de Destajos
        if (Auth::guard('adestajo')->check()) {
            return 'Administrador de Destajos';
        }
        
        // 4. Administrador de Compras
        if (Auth::guard('acompra')->check()) {
            return 'Administrador de Compras';
        }
        
        // 5. Usuario Web (si existe)
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            // Si el modelo User tiene campo 'rol' o similar
            if (isset($user->rol)) {
                return ucfirst($user->rol);
            }
            return 'Usuario';
        }
        
        // Si no hay usuario autenticado
        return 'No autenticado';
    }


    function Comentarios(){
        //Escribir simpre arriba de esta funcion las nuevas funciones.
        //No dejar tanto puto espacio aqui abajo por que si no vale puritita verga la libreria de exportar a excel, PINCHE MAMADA!!!!
    }
?>