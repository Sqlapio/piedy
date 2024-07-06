<?php

use App\Http\Controllers\ApiClientesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificacionesController;
use App\Livewire\Login;
use App\Models\CierreGeneral;
use App\Models\Cliente;
use App\Models\DetalleAsignacion;
use App\Models\VentaServicio;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionesEmail;
use App\Models\Cita;
use App\Models\Membresia;
use App\Models\MovimientoMembresia;
use App\Models\PeriodoNomina;
use App\Models\User;
use Spatie\Browsershot\Browsershot;

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
     * Ruta para cierre general
     * ejecutado solo por el gerente de la tienda
     */
    Route::get('/cierre/general', function () {
        return view('cierre_general');
    })->name('cierre_general');

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
        return view('nomina');
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

// Route::get('reporte/nomina/{id}/{periodo}', function ($id, $periodo) {
//     return view('pdf.reporte', compact('id', 'periodo'));

Route::get('/reporte/nomina', function () {
        return view('pdf.reporte');
    })->name('reporte');

/**FIN GRUPO DE RUTAS------------------------------------------------------------------------------------------*/

Route::get('/pp', function () {

    $primera = '1'.date('mY');
    $segunda = '2'.date('mY');

    $periodo1 = PeriodoNomina::where('status', '0')->where('cod_quincena', $primera)->first();
    $periodo2 = PeriodoNomina::where('status', '0')->where('cod_quincena', $segunda)->first();

    dd($primera, $segunda, $periodo1, $periodo2);

    // $type = 'membresia-activada';
    // $info_cliente = Cliente::where('id', 1610)->first();

    // $mailData = [
    //         'codigo_seguridad'  => '34656545367567568745',
    //         'pm'                => '2356',
    //         'cliente'           => $info_cliente->nombre.' '.$info_cliente->apellido,
    //         'barcode'           => '23432533453.jpg',
    //         'user_email'        => 'gusta.acp@gmail.com',
    //     ];

    // NotificacionesController::notification($mailData, $type);

    // return 'listo';
    //Total de ventas por empleado
    // $products = DB::select('call nomina_quincenal(?, ?)', array('2024-06-01', '2024-06-14'));

    $gerente = User::where('status', '1')->where('tipo_servicio_id', '3')->get(['name']);

    foreach($gerente as $value){
        $_comision_gte = VentaServicio::where('responsable', 'Supergerente')->SUM('comision_gerente');
    }

    //Total de membresias atendidas por empleado
    $user_info = DB::table('movimiento_membresias')
                 ->select('empleado', DB::raw('count(*) as total'))
                 ->where('descripcion', '=', 'consumo en tienda')
                 ->groupBy('empleado')
                 ->get();

    //Total de membresias vendidas
    $membresias = Membresia::all()->SUM('monto');

    //Suma total de membresias atendias por los empleados
    $sum_membresia = MovimientoMembresia::where('descripcion', '=', 'consumo en tienda')->count();

    $empleados = User::whereBetween('tipo_servicio_id',['1', '2'])->where('status', '1')->get();
    $user_quiro = DB::table('venta_servicios')
                 ->select(
                    'empleado', DB::raw('count(*) as total'),
                    DB::raw('SUM(comision_dolares) as Dolares'),
                    DB::raw('SUM(comision_bolivares) as Bolivares')
                    )
                 ->whereBetween('created_at',['2024-06-01 00:00:00', '2024-06-14 23:59:59'])
                 ->groupBy('empleado')
                 ->get();
    dd($empleados, $user_quiro);
    dd($_comision_gte, $gerente, $user_info, 'Monto total de Membresias activas-> '.$membresias, 'Monto total de Membresias atendidas por los empleados-> '.$sum_membresia);

});

Route::get('/ex', function () {
    Browsershot::url('http://piedy.test/pay/ex')

        ->landscape()
        ->save('invoice.pdf');

});
