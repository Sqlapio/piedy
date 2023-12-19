<?php

use App\Http\Controllers\ApiClientesController;
use App\Http\Controllers\LoginController;
use App\Livewire\Login;
use App\Models\Cliente;
use App\Models\VentaServicio;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['validate_db'])->group(function () {

     Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::post('/actualiza/password', [LoginController::class, 'actualiza_password'])->name('actualiza_password');

    Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/dashboard_empleado', function () {
            return view('dashboard_empleado');
        })->name('dashboard_empleado');

        Route::get('/clientes', function () {
            return view('clientes');
        })->name('clientes');

        Route::get('/empleados', function () {
            return view('empleados');
        })->name('empleados');

        Route::get('/servicios', function () {
            return view('servicios');
        })->name('servicios');

        Route::get('/productos', function () {
            return view('productos');
        })->name('productos');

        Route::get('/gastos', function () {
            return view('gastos');
        })->name('gastos');

        Route::get('/cierre/diario', function () {
            return view('cierre_diario');
        })->name('cierre_diario');

        Route::get('/citas', function () {
            return view('citas');
        })->name('citas');

        Route::get('/cabinas', function () {
            return view('cabinas');
        })->name('cabinas');

        Route::get('/venta', function () {
            return view('venta');
        })->name('venta');

        Route::get('/perfil', function () {
            return view('perfil');
        })->name('perfil');

        Route::get('/caja', function () {
            return view('caja');
        })->name('caja');

        Route::get('/agregar/servicios', function () {
            return view('agregar_servicios');
        })->name('agregar_servicios');

        Route::get('/facturar/cliente', function () {
            return view('facturar_cliente');
        })->name('facturar_cliente');

        Route::get('/servicio/asignado', function () {
            return view('servicio_asignado');
        })->name('servicio_asignado');

        Route::get('/historico/servicios', function () {
            return view('historico_servicios');
        })->name('historico_servicios');


        Route::get('/lista/clientes', [ApiClientesController::class, 'lista_clientes'])->name('api.clientes');
        Route::get('/lista/empleados', [ApiClientesController::class, 'lista_empleados'])->name('api.empleados');
        Route::get('/lista/servicios', [ApiClientesController::class, 'lista_servicios'])->name('api.servicios');
        Route::get('/lista/metodo/pago', [ApiClientesController::class, 'metodo_pago'])->name('api.metodo_pago');
        Route::get('/lista/metodo/pago/uno', [ApiClientesController::class, 'metodo_pago_uno'])->name('api.metodo_pago_uno');
        Route::get('/lista/metodo/pago/dos', [ApiClientesController::class, 'metodo_pago_dos'])->name('api.metodo_pago_dos');

        Route::get('/{record}/edit', function () {
            return view('clientes');
        })->name('cliente.edit');
    });
});

Route::get('/p', function () {
    $date = date('m-Y');
    dd($date);

});
