<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Cliente;
use App\Models\TasaBcv;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Disponible;
use Illuminate\Http\Request;
use App\Models\DetalleAsignacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use App\Http\Controllers\NotificacionesController;

class AsignacionController extends Controller
{
    public static function asignacion_servicio($cliente_id, $user_id, $servicio_id)
    {
        try {

            $existe = Disponible::where('empleado_id', $user_id)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('status', 'activo')
            ->first();

            $tasaBcv = TasaBcv::all()->first()->tasa;

            if($existe != null){

                return false;

            }else{

                $servicio   = Servicio::where('id', $servicio_id)->first();

                $disponible = new Disponible();
                $disponible->cod_asignacion  = 'Pca-'.random_int(11111111, 99999999);
                $disponible->cliente_id      = $cliente_id;
                $disponible->empleado_id     = $user_id;
                $disponible->cod_prod_serv   = $servicio->cod_servicio;
                $disponible->servicio_id     = $servicio_id;
                $disponible->acu_servicios   = $servicio->costo;
                $disponible->venta_total     += $servicio->costo;
                $disponible->sucursal_id     = Auth::user()->sucursal_id;
                $disponible->save();

                 /**
                 * Cargamos el servicio principal asigando
                 * en la tabla de detalle de asignacion
                 */
                $detalle_asignacion = new DetalleAsignacion();
                $detalle_asignacion->cod_asignacion  = $disponible->cod_asignacion;
                $detalle_asignacion->cod_prod_serv   = $disponible->cod_prod_serv;
                $detalle_asignacion->empleado_id     = $disponible->empleado_id;
                $detalle_asignacion->cliente_id      = $disponible->cliente_id;
                $detalle_asignacion->servicio_id     = $disponible->servicio_id;
                $detalle_asignacion->costo           = $servicio->costo;
                $detalle_asignacion->costo_bsd       = $servicio->costo * $tasaBcv;
                $detalle_asignacion->fecha           = date('d-m-Y');
                $detalle_asignacion->responsable     = Auth::user()->name;
                $detalle_asignacion->sucursal_id     = Auth::user()->sucursal_id;
                $detalle_asignacion->tipo            = 'servicio';
                $detalle_asignacion->serv_asignacion = $servicio->asignacion;
                $detalle_asignacion->save();

                return true;

            }

        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage());
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }
    }

    public static function asigna_servicio_adicional($servicio_id, $cod_asignacion, $cliente_id)
    {
        try {

            $tasaBcv = TasaBcv::all()->first()->tasa;

            $servicio = Servicio::where('id', $servicio_id)->where('sucursal_id', Auth::user()->sucursal_id)->first();

            $serv_disponible = Disponible::where('cod_asignacion', $cod_asignacion)
            ->where('cliente_id', $cliente_id)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->first();

            if($serv_disponible->status == 'activo'){
                $asigna_status = 1;
            }else{
                $asigna_status = 2;
            }

            $asigna_servicio = new DetalleAsignacion();
            $asigna_servicio->cod_asignacion     = $cod_asignacion;
            $asigna_servicio->cod_prod_serv      = $servicio->cod_servicio;
            $asigna_servicio->empleado_id        = Auth::user()->id;
            $asigna_servicio->cliente_id         = $serv_disponible->cliente_id;

            //Restriccion para servicios duplicados
            $existeServicio = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
            ->where('servicio_id', $servicio_id)
            ->where('cliente_id', $cliente_id)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('status', 1)
            ->first();
            if ($existeServicio) {
                throw new Exception("El servicio ya se encuentra asignado a dicho cliente. Por favor intente con otro", 401);
            }

            $asigna_servicio->servicio_id        = $servicio_id;
            $asigna_servicio->costo              = $servicio->costo;
            $asigna_servicio->costo_bsd          = $servicio->costo * $tasaBcv;
            $asigna_servicio->fecha              = date('d-m-Y');
            $asigna_servicio->responsable        = Auth::user()->name;
            $asigna_servicio->sucursal_id        = Auth::user()->sucursal_id;
            $asigna_servicio->tipo               = 'servicio';
            $asigna_servicio->serv_asignacion    = $servicio->asignacion;
            $asigna_servicio->status             = $asigna_status;
            $asigna_servicio->save();

            //Actualizo el monto total en la tabla de disponibles
            $serv_disponible = Disponible::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('cliente_id', $cliente_id)
            ->first();

            $serv_disponible->acu_servicios   += $servicio->costo;
            $serv_disponible->venta_total     = $serv_disponible->acu_servicios + $serv_disponible->acu_productos;
            $serv_disponible->save();

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage());
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    public static function asigna_producto($producto_id, $cantidad, $cod_asignacion, $cliente_id)
    {
        try {

            $serv_disponible = Disponible::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('cliente_id', $cliente_id)
            ->first();

            if($serv_disponible->status == 'activo'){
                $asigna_status = 1;
            }else{
                $asigna_status = 2;
            }

            $tasaBcv = TasaBcv::all()->first()->tasa;

            $producto = Producto::find($producto_id);

            $asigna_producto = new DetalleAsignacion();
            $asigna_producto->cod_asignacion   = $cod_asignacion;
            $asigna_producto->cod_prod_serv    = $producto->cod_producto;
            $asigna_producto->empleado_id      = Auth::user()->id;
            $asigna_producto->cliente_id       = $cliente_id;
            $asigna_producto->producto_id      = $producto_id;
            $asigna_producto->costo            = $producto->precio_venta * $cantidad;
            $asigna_producto->costo_bsd        = ($producto->precio_venta * $tasaBcv) * $cantidad;
            $asigna_producto->fecha            = date('d-m-Y');
            $asigna_producto->responsable      = Auth::user()->name;
            $asigna_producto->sucursal_id      = Auth::user()->sucursal_id;
            $asigna_producto->tipo             = 'producto';
            $asigna_producto->cantidad         = $cantidad;
            $asigna_producto->status           = $asigna_status;
            $asigna_producto->save();

            //Actualizo el monto total de prodcutos en la tabla de disponibles
            $serv_disponible->acu_productos += $producto->precio_venta * $cantidad;
            $serv_disponible->venta_total   = $serv_disponible->acu_productos + $serv_disponible->acu_servicios;
            $serv_disponible->save();

            LogController::log(Auth::user()->id, 'agrego producto', 'Agrego producto al servicio: '. $asigna_producto->cod_asignacion .' cantidad: '. $asigna_producto->cantidad);

            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage());
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    public static function cerrar_servicio($clave, $cod_asignacion)
    {
        try {

            $user_id = Disponible::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('status', 'activo')
            ->first()->empleado_id;

            if(Hash::check(($clave), User::find($user_id)->password)){
                if(UtilsController::restriccion_serv_vip($cod_asignacion) == true)
                {
                    throw new Exception("No puede facturar dos(2) servicios VIP, por favor elimine uno de ellos y vuelva a intentar", 401);
                }

                $cerrar_disponible = Disponible::where('cod_asignacion', $cod_asignacion)
                ->where('status', 'activo')
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->first()
                ->update([
                    'status'                =>  'cerrado',
                    'status_fac_multiple'   =>  1,
                ]);

                $cerrar_cabina = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
                ->where('sucursal_id', Auth::user()->sucursal_id)
                ->where('status', 1)
                ->get();

                foreach($cerrar_cabina as $item)
                {
                    $item->update([
                        'status' => 2,
                        ]);
                }

                /**
                 * @param $cod_asignacion
                 * Notificacion al empleado via email
                 * ------------------------------------
                 */
                $servicios = Disponible::where('cod_asignacion', $cod_asignacion)
                ->with('detalleAsignaciones', 'user', 'cliente')
                ->first();

                $type = 'servicio';
                $mailData = [
                    'codigo'           => $cod_asignacion ,
                    'user_email'       => $servicios->user->email,
                    'user_fullname'    => $servicios->user->name,
                    'cliente_fullname' => $servicios->cliente->nombre,
                    'fecha_venta'      => $servicios->update_at,
                    'detalle'          => $servicios->detalleAsignaciones,
                ];

                NotificacionesController::notification($mailData, $type);
                /*-------------------------------------------------------------------*/

                return true;

            }else{
                return false;

            }


            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage());
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }
}