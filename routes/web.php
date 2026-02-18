<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Administrador;
use App\Http\Controllers\Reportes\ReporteIngresosController;

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

Route::resource('aproveedoresds', 'App\Http\Controllers\Administradores\ProveedordsController')
->middleware(['auth:administradores']);

Route::resource('adestajos', 'App\Http\Controllers\Administradores\DestajoController')
    ->middleware(['auth:administradores']);

    
// Rutas adicionales para confirmar/rechazar destajos
Route::post('adestajos/{destajo}/confirmar', [App\Http\Controllers\Administradores\DestajoController::class, 'confirmar'])
    ->name('adestajos.confirmar')
    ->middleware(['auth:administradores']);

Route::post('adestajos/{destajo}/rechazar', [App\Http\Controllers\Administradores\DestajoController::class, 'rechazar'])
    ->name('adestajos.rechazar')
    ->middleware(['auth:administradores']);

/**
 * Rutas Aingresos
 */

Route::resource('contratos', 'App\Http\Controllers\Aingresos\ContratoController')
    ->middleware(['auth:aingresos']);

Route::resource('ingresos', 'App\Http\Controllers\Aingresos\IngresoController')
    ->middleware(['auth:aingresos']);


/**
 * Reportes de Ingresos
 * IMPORTANTE: Añade el middleware correspondiente según quién debe acceder
 */
Route::prefix('reportes')->group(function () {
    // Si solo los administradores pueden ver reportes:
    Route::get('/ingresos', [ReporteIngresosController::class, 'index'])
        ->name('reportes.ingresos')
        ->middleware(['auth:administradores']); // O el guard que corresponda
    
    Route::post('/ingresos/generar', [ReporteIngresosController::class, 'generar'])
        ->name('reportes.ingresos.generar')
        ->middleware(['auth:administradores']);
    
    Route::post('/ingresos/exportar-excel', [ReporteIngresosController::class, 'exportarExcel'])
        ->name('reportes.ingresos.exportar.excel')
        ->middleware(['auth:administradores']);
});


 Route::prefix('reportes')->group(function () {
    Route::get('/destajo', [App\Http\Controllers\Reportes\ReporteDestajoController::class, 'index'])
        ->name('reportes.destajo')->middleware(['auth:administradores']);
        
    Route::post('/destajo/exportar', [App\Http\Controllers\Reportes\ReporteDestajoController::class, 'exportar'])
        ->name('reportes.destajo.exportar')->middleware(['auth:administradores']);
});



/**
 * Rutas Adestsajos
 */

Route::resource('productosyservicios', 'App\Http\Controllers\Adestajos\ProductosServiciosController')
    ->middleware(['auth:adestajos']);

Route::resource('proveedoresds', 'App\Http\Controllers\Adestajos\ProveedorController')
    ->middleware(['auth:adestajos']);

Route::resource('destajos', 'App\Http\Controllers\Adestajos\DestajoController')
    ->middleware(['auth:adestajos']);