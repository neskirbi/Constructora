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
                'mail' => $request->mail,
                'pass' => password_hash($request->pass, PASSWORD_DEFAULT),
                'passtemp' => '',
                'principal' => 1
            ]);
            
            return redirect('/')->with('success', 'Administrador creado exitosamente');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    function Logout(){
        if(Auth::guard('administradores')->check()){
            Auth::guard('administradores')->logout();
        }  
        if(Auth::guard('aingresos')->check()){
            Auth::guard('aingresos')->logout();
        }  
        if(Auth::guard('adestajos')->check()){
            Auth::guard('adestajos')->logout();
        }  
        if(Auth::guard('acompras')->check()){
            Auth::guard('acompras')->logout();
        }  

        return redirect('/');
    }





    /**
     * Pruebas
     */

     /**
     * 1️⃣ LLAMA PRIMERO A ESTA - Prueba básica SIN parámetros
     */
    public function testPasswordVerifyBasic()
    {
        $testPassword = 'TestPassword123!'; // Contraseña fija para prueba
        
        // 1. Crear hash
        $hash = password_hash($testPassword, PASSWORD_DEFAULT);
        
        // 2. Verificar correcta
        $isValid = password_verify($testPassword, $hash);
        
        // 3. Verificar incorrecta
        $isInvalid = password_verify('WrongPassword456!', $hash);
        
        // Resultado en array para fácil visualización
        $result = [
            'test_password' => $testPassword,
            'hash_generated' => $hash,
            'password_verify_correct' => $isValid,
            'password_verify_wrong' => $isInvalid,
            'status' => ($isValid && !$isInvalid) ? '✅ FUNCIONA' : '❌ FALLA',
            'message' => $isValid 
                ? 'password_verify funciona: TRUE para correcta, FALSE para incorrecta'
                : 'ERROR: password_verify no funciona correctamente'
        ];
        
        // Puedes hacer:
        // dd($result); // Para ver en pantalla
        // return response()->json($result); // Para API
        // Log::info('Test password_verify', $result); // Para logs
        
        return $result;
    }

    /**
     * 2️⃣ SEGUNDA - Prueba salting (hashes diferentes)
     */
    public function testPasswordSalting()
    {
        $samePassword = 'MiClaveSecreta123';
        
        // Dos hashes para misma contraseña
        $hash1 = password_hash($samePassword, PASSWORD_DEFAULT);
        $hash2 = password_hash($samePassword, PASSWORD_DEFAULT);
        
        $result = [
            'same_password' => $samePassword,
            'hash_1' => $hash1,
            'hash_2' => $hash2,
            'hashes_are_different' => ($hash1 !== $hash2),
            'verify_hash_1' => password_verify($samePassword, $hash1),
            'verify_hash_2' => password_verify($samePassword, $hash2),
            'status' => ($hash1 !== $hash2) ? '✅ SALTING OK' : '❌ SALTING FAIL',
            'message' => ($hash1 !== $hash2) 
                ? 'Salting funciona: hashes diferentes para misma contraseña'
                : 'ERROR: Salting no funciona - hashes iguales'
        ];
        
        return $result;
    }

    /**
     * 3️⃣ TERCERA - Todos los casos de prueba
     */
    public function testAllPasswordCases()
    {
        $testCases = [
            ['input' => 'password123', 'verify' => 'password123', 'should_pass' => true],
            ['input' => 'password123', 'verify' => 'password124', 'should_pass' => false],
            ['input' => 'Test@123', 'verify' => 'test@123', 'should_pass' => false], // case sensitive
            ['input' => '', 'verify' => '', 'should_pass' => true],
            ['input' => 'contraseñaConÑ', 'verify' => 'contraseñaConÑ', 'should_pass' => true],
        ];
        
        $results = [];
        $allPassed = true;
        
        foreach ($testCases as $index => $case) {
            $hash = password_hash($case['input'], PASSWORD_DEFAULT);
            $result = password_verify($case['verify'], $hash);
            $passed = ($result === $case['should_pass']);
            
            if (!$passed) {
                $allPassed = false;
            }
            
            $results[] = [
                'test' => $index + 1,
                'case' => "¿'{$case['input']}' == '{$case['verify']}'?",
                'expected' => $case['should_pass'] ? 'TRUE' : 'FALSE',
                'got' => $result ? 'TRUE' : 'FALSE',
                'status' => $passed ? '✅ PASS' : '❌ FAIL'
            ];
        }
        
        return [
            'tests' => $results,
            'summary' => [
                'total_tests' => count($testCases),
                'passed' => count(array_filter($results, fn($r) => str_contains($r['status'], '✅'))),
                'failed' => count(array_filter($results, fn($r) => str_contains($r['status'], '❌'))),
                'all_passed' => $allPassed
            ],
            'final_status' => $allPassed ? '🎉 TODAS LAS PRUEBAS PASARON' : '⚠️ ALGUNAS PRUEBAS FALLARON'
        ];
    }

    /**
     * 🎯 FUNCIÓN ÚNICA que ejecuta TODAS las pruebas
     */
    public function Logint()
    {
        echo "=== INICIANDO PRUEBAS DE password_verify ===\n\n";
        
        // 1. Prueba básica
        echo "1. PRUEBA BÁSICA:\n";
        $test1 = $this->testPasswordVerifyBasic();
        print_r($test1);
        echo "\n" . str_repeat("-", 50) . "\n\n";
        
        // 2. Prueba salting
        echo "2. PRUEBA DE SALTING:\n";
        $test2 = $this->testPasswordSalting();
        print_r($test2);
        echo "\n" . str_repeat("-", 50) . "\n\n";
        
        // 3. Todos los casos
        echo "3. TODOS LOS CASOS DE PRUEBA:\n";
        $test3 = $this->testAllPasswordCases();
        print_r($test3);
        echo "\n" . str_repeat("-", 50) . "\n\n";
        
        // Resumen final
        $allPassed = $test1['status'] === '✅ FUNCIONA' && 
                     $test2['status'] === '✅ SALTING OK' && 
                     $test3['summary']['all_passed'];
        
        echo "RESUMEN FINAL:\n";
        echo "Prueba básica: " . $test1['status'] . "\n";
        echo "Prueba salting: " . $test2['status'] . "\n";
        echo "Casos de prueba: " . ($test3['summary']['all_passed'] ? '✅ PASÓ' : '❌ FALLÓ') . "\n";
        echo "\n" . ($allPassed ? '🎉 ¡TODAS LAS PRUEBAS PASARON! password_verify FUNCIONA CORRECTAMENTE' : '⚠️ ALGUNAS PRUEBAS FALLARON - REVISA TU CONFIGURACIÓN') . "\n";
        
        return $allPassed;
    }



    public function UpdatePassword(Request $request){
        // Validaciones básicas
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'newPassword' => 'required|min:4|max:8|confirmed',
        ]);

        // Verificar si existe la sesión de reset
        if (!session()->has('password_reset')) {
            return redirect('loginpage')->with('error', 'Sesión expirada. Por favor, inicia sesión nuevamente.');
        }

        $resetData = session('password_reset');

        // Verificar token y email
        if ($resetData['token'] !== $request->token || $resetData['email'] !== $request->email) {
            session()->forget('password_reset');
            return redirect('loginpage')->with('error', 'Datos inválidos.');
        }

        // Verificar expiración (30 minutos)
        if (now()->gt($resetData['expires_at'])) {
            session()->forget('password_reset');
            return redirect('loginpage')->with('error', 'El tiempo para cambiar la contraseña ha expirado.');
        }

        // Buscar el usuario
        $modelClass = $resetData['modelClass'];
        $user = $modelClass::find($resetData['userId']);

        if (!$user) {
            session()->forget('password_reset');
            return redirect('loginpage')->with('error', 'Usuario no encontrado.');
        }

        // Actualizar la contraseña
        $user->pass = password_hash($request->newPassword, PASSWORD_DEFAULT);
        $user->passtemp = ''; // Limpiar contraseña temporal
        $user->save();

        // Limpiar sesión
        session()->forget('password_reset');

        // Hacer login
        Auth::guard($resetData['userType'])->login($user);

        // Registrar logueo si aplica
        $loguearTypes = ['administradores', 'aingresos', 'adestajos', 'acompras'];
        if (in_array($resetData['userType'], $loguearTypes)) {
            return redirect('/')->with('success', 'Contraseña actualizada.');
        }

      
    }

    
}
