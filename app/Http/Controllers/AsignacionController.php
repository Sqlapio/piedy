<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\Servicio;
use App\Models\User;
use App\Models\TasaBcv;
use App\Models\Producto;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AsignacionController extends Controller
{
    public static function asignacion_servicio($cliente_id, $user_id, $servicio_id)
    {
        try {

            $existe = Disponible::where('empleado_id', $user_id)->where('status', 'activo')->first();

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
                $disponible->costo           = $servicio->costo;
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
                $detalle_asignacion->costo           = $disponible->costo;
                $detalle_asignacion->costo_bsd       = $disponible->costo * $tasaBcv;
                $detalle_asignacion->fecha           = date('d-m-Y');
                $detalle_asignacion->responsable     = Auth::user()->name;
                $detalle_asignacion->sucursal_id     = Auth::user()->sucursal_id;
                $detalle_asignacion->tipo            = 'servicio';
                $detalle_asignacion->serv_asignacion = $servicio->asignacion;
                $detalle_asignacion->save();

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

    public static function asigna_servicio_adicional($servicio_id, $cod_asignacion, $cliente_id)
    {
        try {

            $tasaBcv = TasaBcv::all()->first()->tasa;

            $servicio = Servicio::where('id', $servicio_id)->where('sucursal_id', Auth::user()->sucursal_id)->first();

            $info_servPrincipal = Disponible::where('cod_asignacion', $cod_asignacion)
            ->where('status', 'activo')
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->first();

            $asigna_servicio = new DetalleAsignacion();
            $asigna_servicio->cod_asignacion     = $cod_asignacion;
            $asigna_servicio->cod_prod_serv      = $servicio->cod_servicio;
            $asigna_servicio->empleado_id        = Auth::user()->id;
            $asigna_servicio->cliente_id         = $info_servPrincipal->cliente_id;

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
            $asigna_servicio->costo              = $info_servPrincipal->costo;
            $asigna_servicio->costo_bsd          = $info_servPrincipal->costo * $tasaBcv;
            $asigna_servicio->fecha              = date('d-m-Y');
            $asigna_servicio->responsable        = Auth::user()->name;
            $asigna_servicio->sucursal_id        = Auth::user()->sucursal_id;
            $asigna_servicio->tipo               = 'servicio';
            $asigna_servicio->serv_asignacion    = $servicio->asignacion;
            $asigna_servicio->save();

            //code...
        } catch (\Throwable $th) {
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
            $asigna_producto->save();

            //code...
        } catch (\Throwable $th) {
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
                    'status' =>  'cerrado',
                ]);

                $cerrar_cabina = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
                ->where('status', 1)
                ->get();

                foreach($cerrar_cabina as $item)
                {
                    $item->update([
                        'status' => 2,
                        ]);
                }

                return true;

            }else{
                return false;

            }


            //code...
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
