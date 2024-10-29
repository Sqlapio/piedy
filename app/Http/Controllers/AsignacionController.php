<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\Servicio;
use App\Models\User;
use App\Models\CarProducto;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public static function asigancion_servicio($cliente_id, $user_id, $servicio_id)
    {
        try {

            // $existe = Disponible::where('empleado_id', $user_id)->where('status', 'activo')->first();
            $existe = CarProducto::where('user_id', $user_id)->where('status', 1)->first();
            if($existe != null){

                return false;

            }else{

                // $cliente    = Cliente::where('id', $cliente_id)->first();
                $servicio   = Servicio::where('id', $servicio_id)->first();
                // $empleado   = User::where('id', $user_id)->first();

                // $disponible = new Disponible();
                // $disponible->cod_asignacion     = 'Pca-'.random_int(11111111, 99999999);
                // $disponible->cliente_id         = $cliente_id;
                // $disponible->cliente            = $cliente->nombre . ' ' . $cliente->apellido;
                // $disponible->empleado_id        = $user_id;
                // $disponible->empleado           = $empleado->name;
                // $disponible->area_trabajo       = $empleado->area_trabajo;
                // $disponible->cod_servicio       = $servicio->cod_servicio;
                // $disponible->servicio_id        = $servicio_id;
                // $disponible->servicio           = $servicio->descripcion;
                // $disponible->servicio_categoria = $servicio->categoria;
                // $disponible->costo              = $servicio->costo;
                // $disponible->sucursal_id        = $empleado->sucursal_id;
                // $disponible->save();
                $car_serv = new CarProducto();
                $car_serv->cod_asignacion     = 'Pca-'.random_int(11111111, 99999999);
                $car_serv->cod_prod_serv      = $servicio->cod_servicio;
                $car_serv->cliente_id         = $cliente_id;
                $car_serv->user_id        = $user_id;
                $car_serv->precio_venta       = $servicio->costo;
                $car_serv->tipo               = 'servicio';
                $car_serv->status             = 1;
                $car_serv->save();



                 /**
                 * Cargamos el servicio principal asigando
                 * en la tabla de detalle de asignacion
                 */
                // $detalle_asignacion = new DetalleAsignacion();
                // $detalle_asignacion->cod_asignacion     = $disponible->cod_asignacion;
                // $detalle_asignacion->cod_servicio       = $disponible->cod_servicio;
                // $detalle_asignacion->empleado_id        = $disponible->empleado_id;
                // $detalle_asignacion->empleado           = $disponible->empleado;
                // $detalle_asignacion->cliente_id         = $disponible->cliente_id;
                // $detalle_asignacion->cliente            = $disponible->cliente;
                // $detalle_asignacion->servicio_id        = $disponible->servicio_id;
                // $detalle_asignacion->servicio           = $servicio->descripcion;
                // $detalle_asignacion->servicio_categoria = $servicio->categoria;
                // $detalle_asignacion->costo              = $disponible->costo;
                // $detalle_asignacion->fecha              = date('d-m-Y');
                // $detalle_asignacion->save();

                return true;

            }

        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }
    }
}
