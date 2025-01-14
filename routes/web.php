<?php

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\User;
use App\Livewire\Login;
use App\Models\Cliente;
use App\Models\TasaBcv;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Membresia;
use App\Models\Disponible;
use App\Models\Inventario;
use Flowframe\Trend\Trend;
use App\Models\ServicioUser;
use App\Models\CierreGeneral;
use App\Models\PeriodoNomina;
use App\Models\VentaServicio;
use App\Models\AsignarProducto;
use Flowframe\Trend\TrendValue;
use App\Mail\NotificacionesEmail;
use App\Models\DetalleAsignacion;
use App\Models\InventarioSucursal;
use App\Models\NotificacionMasiva;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoMembresia;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ApiClientesController;
use App\Http\Controllers\RequisicionController;
use App\Http\Controllers\NotificacionesController;

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

Route::get('/   ', function () {
    return view('welcome');
})->name('welcome');

Route::get('/l/e', function () {
    return view('login-externo');
})->name('login-externo');

Route::get('/p/e', function () {
    return view('pago-externo');
})->name('pago-externo');

Route::get('/pay/ex', function () {
    return view('pago-exitoso');
})->name('pago-exitoso');
Route::get('/rg', function () {
    return view('pdf.reporte-general');
});

Route::get('/lista/srv/x/facturar', [ApiClientesController::class, 'servicios_por_facturar'])->name('api.servicios_por_facturar');


Route::post('/actualiza/password', [LoginController::class, 'actualiza_password'])->name('actualiza_password');

/**
 *--------------------------------------------------------------------------------------------------------------
 * Grupo de Rutas
 *--------------------------------------------------------------------------------------------------------------
 */

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    /**
     * ---------------------------------------------------------
     * Menu principal de la aplicación que se utiliza en tienda
     * ---------------------------------------------------------
     */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /**-------------------------------------------------------*/



    /**
     * ---------------------------------------------------------
     * Rutas del sistema
     * ---------------------------------------------------------
     */
    Route::get('/dashboard_empleado', function () {
        return view('dashboard_empleado');
    })->name('dashboard_empleado');

    Route::get('/empleados', function () {
        return view('empleados');
    })->name('empleados');

    Route::get('/clientes', function () {
        return view('clientes');
    })->name('clientes');

    Route::get('/empleados', function () {
        return view('empleados');
    })->name('empleados');

    Route::get('/servicios', function () {
        return view('servicios');
    })->name('servicios');

    Route::get('/modal', function () {
        return view('modal-agenda-filament');
    })->name('modal');

    Route::get('/productos', function () {
        return view('productos');
    })->name('productos');

    /**
     * Ruta creadas para el modulo de inventario
     */
    Route::prefix('productos')->group(function () {

        Route::get('/crear', function () {
            return view('productos.crear_producto');
        })->name('crear_producto');

        Route::get('/asignar', function () {
            return view('productos.asignar_producto');
        })->name('asignar_producto');

        Route::get('/venta', function () {
            return view('productos.vender_producto');
        })->name('vender_producto');
    });

    /**
     * Rutas para cierres parciales
     */
    Route::get('/cierre/diario', function () {
        return view('cierre_diario');
    })->name('cierre_diario');

    /**
     * Rutas para Materiales
     */
    Route::get('/material', function () {
        return view('asignar-material');
    })->name('material');

    /**
     * Rutas para recepcion de inventario
     */
    Route::get('/recepcion/inventario', function () {
        return view('recepcion-inventario');
    })->name('recepcion-inventario');



    /**
     * Ruta para cierre general
     * ejecutado solo por el gerente de la tienda
     */
    Route::get('/cierre/general', function () {
        return view('cierre_general');
    })->name('cierre_general');

    /**
     * Ruta para cierre financiero
     */
    Route::get('/c/f', function () {
        return view('cierre-financiero');
    })->name('cierre-financiero');

    /**
     * Rutas para gastos
     */
    Route::get('/gastos', function () {
        return view('gastos');
    })->name('gastos');


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

    /**
     * Rutas para agregas servicios o productos
     */
    Route::get('/agregar/servicios', function () {
        return view('agregar_servicios');
    })->name('agregar_servicios');

    Route::get('/agregar/productos', function () {
        return view('agregar_productos');
    })->name('agregar_productos');

    Route::get('/caja/producto', function () {
        return view('caja-producto');
    })->name('caja_producto');

    Route::get('/inventario', function () {
        return view('inventario');
    })->name('inventario');
    /**********************************************/

    Route::get('/facturar/cliente', function () {
        return view('facturar_cliente');
    })->name('facturar_cliente');

    Route::get('/servicio/asignado', function () {
        return view('servicio_asignado');
    })->name('servicio_asignado');

    Route::get('/historico/servicios', function () {
        return view('historico_servicios');
    })->name('historico_servicios');

    Route::get('/g/m', function () {
        return view('menu-gift-membresia');
    })->name('menu-gift-membresia');

    Route::get('/g/c', function () {
        return view('gift-card');
    })->name('gift-card');

    Route::get('/m', function () {
        return view('membresia');
    })->name('membresia');

    /**-------------------------------------------------------*/

    /**
     * ---------------------------------------------------------
     * RUTAS:
     * Modulo del Empleado
     * para visualizar el listado de servicios realizados
     * ---------------------------------------------------------
     */
    Route::get('/l/srv', function () {
        return view('lista-servicio-empleado');
    })->name('lista-servicio-empleado');
    /**-------------------------------------------------------*/


    /**
     * ---------------------------------------------------------
     * RUTAS:
     * Modulo de Nomina
     * ---------------------------------------------------------
     */

    Route::get('/nomina', function () {
        return view('nom-quiropedista');
    })->name('nomina');

    Route::get('/reporte', function () {
        return view('reporte');
    })->name('reporte');

    Route::get('/reporte/general', function () {
        return view('reporte-general');
    })->name('reporte-general');

    Route::prefix('n')->group(function () {

        Route::get('/q', function () {
            return view('nom-quiropedista');
        })->name('nom-quiropedista');

        Route::get('/m', function () {
            return view('nom-manicurista');
        })->name('nom-manicurista');

        Route::get('/e', function () {
            return view('nom-encargado');
        })->name('nom-encargado');
    });

    /**-------------------------------------------------------*/


    Route::get('/lista/clientes', [ApiClientesController::class, 'lista_clientes'])->name('api.clientes');
    Route::get('/lista/empleados', [ApiClientesController::class, 'lista_empleados'])->name('api.empleados');
    Route::get('/lista/empleados/n', [ApiClientesController::class, 'lista_empleados_nomina'])->name('api.empleados.nomina');
    Route::get('/lista/servicios', [ApiClientesController::class, 'lista_servicios'])->name('api.servicios');
    Route::get('/lista/metodo/pago', [ApiClientesController::class, 'metodo_pago'])->name('api.metodo_pago');
    Route::get('/lista/metodo/pago/uno', [ApiClientesController::class, 'metodo_pago_uno'])->name('api.metodo_pago_uno');
    Route::get('/lista/metodo/pago/dos', [ApiClientesController::class, 'metodo_pago_dos'])->name('api.metodo_pago_dos');
    Route::get('/lista/productos', [ApiClientesController::class, 'lista_productos'])->name('api.lista_productos');
    Route::get('/lista/categoria', [ApiClientesController::class, 'categoria_producto'])->name('api.categoria_producto');
    Route::get('/lista/periodo', [ApiClientesController::class, 'meses'])->name('api.meses');
    Route::get('/lista/metodo/pago/ref', [ApiClientesController::class, 'metodo_pago_ref'])->name('api.metodo_pago_ref');
    Route::get('/lista/periodo/n', [ApiClientesController::class, 'lista_periodo_nomina'])->name('api.periodo.nomina');
    Route::get('/lista/metodo/pago/multiple', [ApiClientesController::class, 'metodo_pago_multiple'])->name('api.metodo_pago_multiple');

    Route::get('/{record}/edit', function () {
        return view('clientes');
    })->name('cliente.edit');
});


Route::get('/reporte/nomina', function () {
    return view('pdf.reporte');
})->name('reporte');

/**
 * RUTAS PARA CONFIRMACION Y CANCELACION DE CITAS
 */

Route::get('/confirmacion/{cita_id}', [AgendaController::class, 'confirmacion'])->name('cita-confirmacion');

Route::get('/cancelacion/{cita_id}', [AgendaController::class, 'cancelacion'])->name('cita-cancelacion');

Route::get('/requisicion/{codigo}/{sucursal_id}', [RequisicionController::class, 'detalleRequisicion'])->name('detalle-requisicion');

Route::get('/detalle/srv/{codigo}', [CajaController::class, 'detalleServicio'])->name('detalle-servicio');



/**FIN GRUPO DE RUTAS------------------------------------------------------------------------------------------*/

Route::get('/ex', function () {

    // $clientes = Cliente::all();

    // foreach ($clientes as $cliente) {
    //     $nombre = $cliente->nombre;
    //     $apellido = $cliente->apellido;
    //     $cliente->update([
    //         'nombre' => $nombre. ' ' . $apellido
    //     ]);
    // }

    // $ps = InventarioSucursal::all()
    // foreach ($ps as $item) {
    //     $p = Producto::where('id', $item->producto_id)->first();
    //     // dd($p->cod_producto);
    //     InventarioSucursal::where('producto_id', $p->id)->update([
    //         'cod_producto' => $p->cod_producto
    //     ]);
    // }


    // dd(1);

    $in = Inventario::where('unidad', null)->get();
    foreach ($in as $item) {
        // dump($item);
        $unidad = Producto::where('id', $item->producto_id)->first();
        // dd($unidad);
        Inventario::where('id', $item->id)->update([
            'contenido_neto' => $unidad->contenido_neto
        ]);
    }
    dd(1);

    dd(now()->format('Y-m-d H:i:s.u'), date('Y-m-d H:i:s.u'));
});
