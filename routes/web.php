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
use Illuminate\Console\Scheduling\Schedule;


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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::post('/actualiza/password', [LoginController::class, 'actualiza_password'])->name('actualiza_password');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    /**
     * Menu principal de la aplicación
     * que se utiliza en tienda
     */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');



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


    Route::get('/lista/clientes', [ApiClientesController::class, 'lista_clientes'])->name('api.clientes');
    Route::get('/lista/empleados', [ApiClientesController::class, 'lista_empleados'])->name('api.empleados');
    Route::get('/lista/servicios', [ApiClientesController::class, 'lista_servicios'])->name('api.servicios');
    Route::get('/lista/metodo/pago', [ApiClientesController::class, 'metodo_pago'])->name('api.metodo_pago');
    Route::get('/lista/metodo/pago/uno', [ApiClientesController::class, 'metodo_pago_uno'])->name('api.metodo_pago_uno');
    Route::get('/lista/metodo/pago/dos', [ApiClientesController::class, 'metodo_pago_dos'])->name('api.metodo_pago_dos');
    Route::get('/lista/productos', [ApiClientesController::class, 'lista_productos'])->name('api.lista_productos');
    Route::get('/lista/categoria', [ApiClientesController::class, 'categoria_producto'])->name('api.categoria_producto');
    Route::get('/lista/periodo', [ApiClientesController::class, 'meses'])->name('api.meses');
    Route::get('/lista/metodo/pago/ref', [ApiClientesController::class, 'metodo_pago_ref'])->name('api.metodo_pago_ref');

    Route::get('/{record}/edit', function () {
        return view('clientes');
    })->name('cliente.edit');
});

Route::get('/pp', function () {

    $view = 'emails.prueba-email';
    $mailData = [
        'nombre' => 'gustavo'
    ];
    Mail::to('gusta.acp@gmail.com')->send(new NotificacionesEmail($mailData, $view));

    return 'todo bien';

    // Schedule->command('app:send-mail-command')
    //      ->daily()
    //      ->emailOutputTo('gusta.acp@gmail.com');

    // $fecha_anterior =  date("d-m-Y", strtotime(date("d-m-Y") . "-15 day"));
    // $clientes = VentaServicio::where('fecha_venta', $fecha_anterior)->get();
    // $clientes = DB::table('venta_servicios')
    // ->select('cliente_id', 'cliente')
    // ->groupBy('cliente_id', 'cliente')
    // ->where('fecha_venta', $fecha_anterior)
    // ->get();

    // dd($clientes, $fecha_anterior);
    // foreach($clientes as $item)
    // {
    //     $data = Cliente::find($item->cliente_id);
    //     if($data->email != null){
    //         dump($data->email);

    //     }
    //     // $view = 'emails.correo_masivo';
    //     // $mailData = [
    //     //     'cliente' => $cliente->nombre.' '.$cliente->apellido
    //     // ];

    //     // Mail::to($cliente->email)->send(new NotificacionesEmail($mailData, $view));

    // }
    // dd($fecha_anterior);

    // // $ultimo_cierre = CierreGeneral::latest()->first()->fecha;

    // // $fecha_anterior =  date("d-m-Y", strtotime(date($ultimo_cierre) . "+1 day"));
    // // dd($ultimo_cierre, $fecha_anterior);

    // // $fechaIni = '2024-01-01';
    // // $fechaFin = '2024-01-31';

    // // $resultados = DB::select("CALL Sp_ObtenerComisionesEmpleados(?, ?)", array($fechaIni, $fechaFin));

    // // dd(DetalleAsignacion::where('fecha', date('d-m-Y'))->count());

    // $fecha_posterior =  date("d-m-Y", strtotime(date("d-m-Y") . "+1 day"));
    // $citas_posteriores = Cita::where('fecha', Carbon::parse(date($fecha_posterior))->isoFormat('dddd, D MMM '))->get();

    // dd($fecha_posterior, $citas_posteriores);

    // $params=array(
    //     'token' => '863lb4l0wmldpl3s',
    //     'to' => '+5804142682379',
    //     'body' => 'Prueba de mensaje desde piedy code'
    //     );
    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //       CURLOPT_URL => "https://api.ultramsg.com/instance83564/messages/chat",
    //       CURLOPT_RETURNTRANSFER => true,
    //       CURLOPT_ENCODING => "",
    //       CURLOPT_MAXREDIRS => 10,
    //       CURLOPT_TIMEOUT => 30,
    //       CURLOPT_SSL_VERIFYHOST => 0,
    //       CURLOPT_SSL_VERIFYPEER => 0,
    //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //       CURLOPT_CUSTOMREQUEST => "POST",
    //       CURLOPT_POSTFIELDS => http_build_query($params),
    //       CURLOPT_HTTPHEADER => array(
    //         "content-type: application/x-www-form-urlencoded"
    //       ),
    //     ));

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);

    //     curl_close($curl);

    //     if ($err) {
    //       echo "cURL Error #:" . $err;
    //     } else {
    //       echo $response;
    //     }

});
