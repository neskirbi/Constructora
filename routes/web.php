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

Route::post('acontratos/{id}/ampliacion-tiempo', [App\Http\Controllers\Aingresos\ContratoController::class, 'storeAmpliacionTiempo'])->name('acontratos.ampliacion-tiempo');
Route::post('acontratos/{id}/ampliacion-monto', [App\Http\Controllers\Aingresos\ContratoController::class, 'storeAmpliacionMonto'])->name('acontratos.ampliacion-monto');
Route::delete('acontratos/ampliacion-tiempo/{id}', [App\Http\Controllers\Aingresos\ContratoController::class, 'destroyAmpliacionTiempo'])->name('acontratos.ampliacion-tiempo.destroy');
Route::delete('acontratos/ampliacion-monto/{id}', [App\Http\Controllers\Aingresos\ContratoController::class, 'destroyAmpliacionMonto'])->name('acontratos.ampliacion-monto.destroy');

Route::resource('ingresos', 'App\Http\Controllers\Aingresos\IngresoController')
    ->middleware(['auth:aingresos']);
Route::put('/ingresos/{id}/facturacion', [App\Http\Controllers\Aingresos\IngresoController::class, 'updateFacturacion'])
    ->name('ingresos.update.facturacion') ->middleware(['auth:aingresos']);
    Route::get('/ingresos/ultimo/{id}', [App\Http\Controllers\Aingresos\IngresoController::class, 'getUltimoIngreso'])
    ->name('ingresos.ultimo');
    


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
    
    // Reporte de Compras 
    Route::get('/compra', [App\Http\Controllers\Reportes\ReporteCompraController::class, 'index'])
        ->name('reportes.compra');
        
    Route::post('/compra/exportar', [App\Http\Controllers\Reportes\ReporteCompraController::class, 'exportar'])
        ->name('reportes.compra.exportar');

    // Reporte de Productos y servicios 
    Route::get('/ps', [App\Http\Controllers\Reportes\ReportePSController::class, 'index'])
        ->name('reportes.ps');
        
    Route::post('/ps/exportar', [App\Http\Controllers\Reportes\ReportePSController::class, 'exportar'])
        ->name('reportes.ps.exportar');

        // Reporte de Proveedores
    Route::get('/proveedores', [App\Http\Controllers\Reportes\ReporteProveedoresController::class, 'index'])
        ->name('reportes.proveedores');

    Route::post('/proveedores/exportar', [App\Http\Controllers\Reportes\ReporteProveedoresController::class, 'exportar'])
    ->name('reportes.proveedores.exportar');
    
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

// routes/web.php

Route::get('requisiciones', [App\Http\Controllers\Acompras\RequisicionController::class, 'index'])
    ->name('compras.requisiciones.index')
    ->middleware(['auth:acompras']);

Route::get('requisiciones/create', [App\Http\Controllers\Acompras\RequisicionController::class, 'create'])
    ->name('compras.requisiciones.create')
    ->middleware(['auth:acompras']);

Route::get('requisiciones/show/{contratoId}', [App\Http\Controllers\Acompras\RequisicionController::class, 'show'])
    ->name('compras.requisiciones.show')
    ->middleware(['auth:acompras']);

Route::post('requisiciones/procesar-excel', [App\Http\Controllers\Acompras\RequisicionController::class, 'procesarExcel'])
    ->name('compras.requisiciones.procesar-excel')
    ->middleware(['auth:acompras']);

Route::post('requisiciones/eliminar', [App\Http\Controllers\Acompras\RequisicionController::class, 'eliminarItem'])
    ->name('compras.requisiciones.eliminar')
    ->middleware(['auth:acompras']);

Route::post('requisiciones/borrar-grupo/{contratoId}', [App\Http\Controllers\Acompras\RequisicionController::class, 'borrarGrupo'])
    ->name('compras.requisiciones.borrar-grupo')
    ->middleware(['auth:acompras']);

Route::post('requisiciones/confirmar/{contratoId}', [App\Http\Controllers\Acompras\RequisicionController::class, 'confirmarCompraPorContrato'])
    ->name('compras.requisiciones.confirmar')
    ->middleware(['auth:acompras']);

    Route::post('requisiciones/agregar-proveedor', [App\Http\Controllers\Acompras\RequisicionController::class, 'agregarProveedor'])
    ->name('compras.requisiciones.agregar-proveedor')
    ->middleware(['auth:acompras']);

Route::delete('requisiciones/eliminar-proveedor/{id}', [App\Http\Controllers\Acompras\RequisicionController::class, 'eliminarProveedor'])
    ->name('compras.requisiciones.eliminar-proveedor')
    ->middleware(['auth:acompras']);

    // Rutas para requisiciones
Route::post('requisiciones/guardar-item-completo', [App\Http\Controllers\Acompras\RequisicionController::class, 'guardarItemCompleto'])
    ->name('compras.requisiciones.guardar-item-completo')
    ->middleware(['auth:acompras']);

Route::delete('requisiciones/eliminar-proveedor-item/{id}', [App\Http\Controllers\Acompras\RequisicionController::class, 'eliminarProveedorItem'])
    ->name('compras.requisiciones.eliminar-proveedor-item')
    ->middleware(['auth:acompras']);

Route::get('requisiciones/resumen/{id}', [App\Http\Controllers\Acompras\RequisicionController::class, 'resumen'])
    ->name('compras.requisiciones.resumen')
    ->middleware(['auth:acompras']);

/**
 * Generales
 */

    

Route::resource('proveedoresds', 'App\Http\Controllers\General\ProveedorController')
    ->middleware(['auth:adestajos,acompras']);

Route::resource('productosyservicios', 'App\Http\Controllers\General\ProductosServiciosController')
    ->middleware(['auth:adestajos,acompras']);

Route::post('NuevoPS', [App\Http\Controllers\General\ProductosServiciosController::class, 'NuevoPS'])
->name('NuevoPS');

Route::post('/proveedores/guardar', [App\Http\Controllers\General\ProveedorController::class, 'guardarProveedor'])->name('proveedoresds.guardar');


/**
 * Soporte
 */

Route::get('SacarTotales', [App\Http\Controllers\Soporte\TareasController::class, 'SacarTotales']);