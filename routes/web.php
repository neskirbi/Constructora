<?php

use Illuminate\Support\Facades\Route;
use App\Models\Administrador;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Verificar si hay administradores registrados
Route::get('/', function () {
    $hayAdministradores = Administrador::count() > 0;
    
    if ($hayAdministradores) {
        
        if(Auth::guard('administradores')->check()){
        return redirect('administradores');
        }  
        if(Auth::guard('aingresos')->check()){
            return redirect('ingresos');
        }  
        if(Auth::guard('adestajos')->check()){
            return redirect('destajos');
        }  
        if(Auth::guard('acompras')->check()){
            return redirect('vehiculossoporte');
        }  

        return view('login');
    } else {
        return redirect()->route('start');
    }
});

// Ruta start - solo accesible si NO hay administradores
Route::get('/start', function () {
    $hayAdministradores = Administrador::count() > 0;
    
    if ($hayAdministradores) {
        return redirect('/');
    }
    
    return view('start');
})->name('start');


// Login (solo si hay administradores)
Route::get('logout', 'App\Http\Controllers\LoginController@Logout');

// Login (solo si hay administradores)
Route::post('login', 'App\Http\Controllers\LoginController@Login');

// Registro (desde start)
Route::post('reg', 'App\Http\Controllers\LoginController@Reg');

//Reset de pass
Route::post('update-password', 'App\Http\Controllers\LoginController@UpdatePassword');

/**
 * Rutas Administradores
 */

Route::resource('administradores', 'App\Http\Controllers\Administradores\AdministradorController')
    ->middleware(['auth:administradores']);

    Route::resource('acontratos', 'App\Http\Controllers\Administradores\ContratoController')
    ->middleware(['auth:administradores']);

    Route::resource('aingresos', 'App\Http\Controllers\Administradores\IngresoController')
    ->middleware(['auth:administradores']);
/**
 * Rutas Aingresos
 */

Route::resource('contratos', 'App\Http\Controllers\Aingresos\ContratoController')
    ->middleware(['auth:aingresos']);

Route::resource('ingresos', 'App\Http\Controllers\Aingresos\IngresoController')
    ->middleware(['auth:aingresos']);