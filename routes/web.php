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
Route::post('login', 'App\Http\Controllers\LoginController@Login');

// Registro (desde start)
Route::post('reg', 'App\Http\Controllers\LoginController@Reg');

// Home/dashboard (protegido)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    });
});