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
            return redirect('compras');
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



Route::resource('aproductosyservicios', 'App\Http\Controllers\Administradores\ProductosServiciosController')
    ->middleware(['auth:administradores']);



// Rutas para confirmar/rechazar compras
Route::resource('acompras', 'App\Http\Controllers\Administradores\CompraController')
    ->middleware(['auth:administradores']);

Route::put('compras/{id}/confirmar', [App\Http\Controllers\Administradores\CompraController::class, 'confirmar'])
    ->name('compras.confirmar')
    ->middleware(['auth:administradores']);

Route::put('compras/{id}/rechazar', [App\Http\Controllers\Administradores\CompraController::class, 'rechazar'])
    ->name('compras.rechazar')
    ->middleware(['auth:administradores']);
    
    
// Rutas adicionales para confirmar/rechazar destajos
Route::resource('adestajos', 'App\Http\Controllers\Administradores\DestajoController')
    ->middleware(['auth:administradores']);

Route::put('destajos/{id}/confirmar', [App\Http\Controllers\Administradores\DestajoController::class, 'confirmar'])
    ->name('destajos.confirmar')
    ->middleware(['auth:administradores']);

Route::put('destajos/{id}/rechazar', [App\Http\Controllers\Administradores\DestajoController::class, 'rechazar'])
    ->name('destajos.rechazar')
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
Route::prefix('reportes')->middleware(['auth:administradores'])->group(function () {
    

// Reporte de Ingresos
    Route::get('/contratos', [App\Http\Controllers\Reportes\ReporteContratoController::class, 'index'])
        ->name('reportes.contratos');
    
    Route::post('/contratos/generar', [App\Http\Controllers\Reportes\ReporteContratoController::class, 'generar'])
        ->name('reportes.contratos.generar');

    // Reporte de Ingresos
    Route::get('/ingresos', [App\Http\Controllers\Reportes\ReporteIngresosController::class, 'index'])
        ->name('reportes.ingresos');
    
    Route::post('/ingresos/generar', [App\Http\Controllers\Reportes\ReporteIngresosController::class, 'generar'])
        ->name('reportes.ingresos.generar');
    
    Route::post('/ingresos/exportar-excel', [App\Http\Controllers\Reportes\ReporteIngresosController::class, 'exportarExcel'])
        ->name('reportes.ingresos.exportar.excel');
    
    // Reporte de Destajos
    Route::get('/destajo', [App\Http\Controllers\Reportes\ReporteDestajoController::class, 'index'])
        ->name('reportes.destajo');
        
    Route::post('/destajo/exportar', [App\Http\Controllers\Reportes\ReporteDestajoController::class, 'exportar'])
        ->name('reportes.destajo.exportar');
    
    // Reporte de Compras (para agregar después)
    Route::get('/compra', [App\Http\Controllers\Reportes\ReporteCompraController::class, 'index'])
        ->name('reportes.compra');
        
    Route::post('/compra/exportar', [App\Http\Controllers\Reportes\ReporteCompraController::class, 'exportar'])
        ->name('reportes.compra.exportar');
    
});





/**
 * Rutas Adestsajos
 */




Route::resource('destajos', 'App\Http\Controllers\Adestajos\DestajoController')
    ->middleware(['auth:adestajos']);


/**
 * Rutas Compras 
 */


Route::resource('compras', 'App\Http\Controllers\Acompras\CompraController')
->middleware(['auth:acompras']);


/**
 * Generales
 */

    

Route::resource('proveedoresds', 'App\Http\Controllers\General\ProveedorController')
    ->middleware(['auth:adestajos,acompras']);

Route::resource('productosyservicios', 'App\Http\Controllers\General\ProductosServiciosController')
    ->middleware(['auth:adestajos,acompras']);

