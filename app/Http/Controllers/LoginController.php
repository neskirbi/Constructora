<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Administrador;
use App\Models\AIngreso;
use App\Models\ADestajo;
use App\Models\ACompra;

class LoginController extends Controller
{
    function Login(Request $request){
        //return $request;
        // Función auxiliar para manejar el login con passtemp
        function handleLogin($user, $password, $guard, $modelClass, $redirectOnSuccess = '/', $loguear = false) {
            // Verificar si el usuario tiene passtemp configurado
            if(isset($user->passtemp) && $user->passtemp != '') {
                // Si la contraseña ingresada coincide con el passtemp
                if($user->passtemp == $password) {
                    // Generar token único
                    $token = bin2hex(random_bytes(32));
                    
                    // Guardar el token temporalmente en sesión
                    session([
                        'password_reset' => [
                            'token' => $token,
                            'userId' => $user->id,
                            'userType' => $guard,
                            'modelClass' => $modelClass,
                            'email' => $user->mail,
                            'expires_at' => now()->addMinutes(30)
                        ]
                    ]);
                    
                    // Mostrar vista de cambio de contraseña con el token
                    return view('passreset', [
                        'token' => $token,
                        'email' => $user->mail
                    ]);
                } else {
                    return redirect('/')->with('error', '¡Error de contraseña!');
                }
            } else {
                // Si no tiene passtemp, verificar contraseña normal
                if(!password_verify($password, $user->pass)) {
                    return redirect('/')->with('error', '¡Error de contraseña!');
                }
                Auth::guard($guard)->login($user);
               
                return redirect($redirectOnSuccess);
            }
        }   

        
        $administrador = Administrador::where(['mail' => $request->username])->first();
        if($administrador){
            return handleLogin($administrador, $request->password, 'administradores', Administrador::class, '/', true);
        }

        
        $aingreso = AIngreso::where(['mail' => $request->username])->first();
        if($aingreso){
            return handleLogin($aingreso, $request->password, 'aingresos', AIngreso::class, '/', true);
        }

        
        $adestajo = ADestajo::where(['mail' => $request->username])->first();
        if($adestajo){
            return handleLogin($adestajo, $request->password, 'adestajos', ADestajo::class, '/', true);
        }

        
        $acompra = ACompra::where(['mail' => $request->username])->first();
        if($acompra){
            return handleLogin($acompra, $request->password, 'acompras', ACompra::class, '/', true);
        }

       
        return redirect('/')->with('error', '¡Correo no registrado!');
    }

    function Reg(Request $request)
{
    try {
        $admin = Administrador::create([
            'id' => GetUuid(),
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'mail' => $request->username,
            'pass' => password_hash($request->password, PASSWORD_DEFAULT),
            'passtemp' => '',
            'principal' => 1,
        ]);
        
        return redirect('/')->with('success', 'Administrador creado exitosamente');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error al registrar: ' . $e->getMessage());
    }
}
}
