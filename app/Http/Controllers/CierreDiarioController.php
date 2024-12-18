<?php

namespace App\Http\Controllers;

use App\Models\CajaChica;
use App\Models\CierreDiario;
use App\Models\DetalleAsignacion;
use App\Models\TasaBcv;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CierreDiarioController extends Controller
{
    public static function cierreDiario($ref_debito, $monto_ref_debito, $ref_credito, $monto_ref_credito, $ref_visaMaster, $monto_ref_visaMaster, $observaciones = null)
    {
        try {

            $query = CierreDiario::where('fecha', date('d-m-Y'))->count();

            if($query >= 2){
                throw new Exception("Usted no puede relizar mas de 2 cierres al dia. Por favor comuniquese con el administrador", 401);

            }else{

                /** Responsable del cierre */
                $user = Auth::user();
                
                /** totales de pagos en Dolares*/
                $total_efectivo_usd = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Efectivo Usd')->sum('pago_usd');
                $total_efectivo_usd_productos = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoUsd', 'Efectivo Usd')->sum('montoUsd');

                $total_zelle = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Zelle')->sum('pago_usd');
                $total_zelle_productos = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoUsd', 'Zelle')->sum('montoUsd');

                /** totales de pagos en Bolivares*/
                $total_bs                   = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

                $total_efectivo_bsd         = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago_dos', 'Efectivo Bsd')->sum('pago_bsd');
                $total_pago_movil_bsd       = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago_dos', 'Pago movil')->sum('pago_bsd');
                $total_punto_venta_bsd      = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago_dos', 'Punto de venta')->sum('pago_bsd');
                $total_transferencia_bsd    = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago_dos', 'Transferencia')->sum('pago_bsd');

                /** Total de pago en bolivares para venta de productos */
                $totalprod_bsd               = VentaProducto::where('fecha_venta', date('d-m-Y'))->sum('montoBsd');
                
                $totalprod_efectivo_bsd      = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoBsd', 'Efectivo Bsd')->sum('montoBsd');
                $totalprod_pago_movil_bsd    = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoBsd', 'Pago movil')->sum('montoBsd');
                $totalprod_punto_venta_bsd   = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoBsd', 'Punto de venta')->sum('montoBsd');
                $totalprod_transferencia_bsd = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoBsd', 'Transferencia')->sum('montoBsd');
                
                
                


                /**Logica para evitar que una persona haga mas de dos cierres en el dia */
                $existe_cierre = CierreDiario::where('fecha', date('d-m-Y'))->where('responsable', $user->name)->first();

                if(isset($existe_cierre)){
                    throw new Exception("Accion no permitida.! Este usuario ya realizo un cierre diario. Por favor comuniquese con el administrador", 401);
                }

                $cierre = new CierreDiario();
                $cierre->total_dolares_efectivo  = $total_efectivo_usd + $total_efectivo_usd_productos;
                $cierre->total_dolares_zelle     = $total_zelle + $total_zelle_productos;
                $cierre->total_bolivares         = $total_bs + $totalprod_bsd;
                
                $cierre->total_pago_movil_bsd    = $total_pago_movil_bsd + $totalprod_pago_movil_bsd;
                $cierre->total_punto_venta_bsd   = $total_punto_venta_bsd + $totalprod_punto_venta_bsd;
                $cierre->total_transferencia_bsd = $total_transferencia_bsd + $totalprod_transferencia_bsd;
                $cierre->total_efectivo_bsd      = $total_efectivo_bsd +$totalprod_efectivo_bsd;
                
                $cierre->ref_debito              = $ref_debito;
                $cierre->monto_ref_debito        = (str_replace(',', '.', str_replace('.', '', $monto_ref_debito))) == null ? 0.00 : str_replace(',', '.', str_replace('.', '', $monto_ref_debito));
                $cierre->ref_credito             = $ref_credito;
                $cierre->monto_ref_credito       = (str_replace(',', '.', str_replace('.', '', $monto_ref_credito))) == null ? 0.00 : str_replace(',', '.', str_replace('.', '', $monto_ref_credito));
                $cierre->ref_visaMaster          = $ref_visaMaster;
                $cierre->monto_ref_visaMaster    = (str_replace(',', '.', str_replace('.', '', $monto_ref_visaMaster))) == null ? 0.00 : str_replace(',', '.', str_replace('.', '', $monto_ref_visaMaster));
                $cierre->fecha                   = date('d-m-Y');
                $cierre->responsable             = $user->name;
                $cierre->observaciones           = $observaciones;
                $cierre->sucursal_id             = $user->sucursal_id;
                //Totales en dolares y bolivares
                $cierre->total_cierre_usd        = $cierre->total_dolares_efectivo + $cierre->total_dolares_zelle;
                $cierre->total_cierre_bsd        = $cierre->total_bolivares + $cierre->monto_ref_debito + $cierre->monto_ref_credito + $cierre->monto_ref_visaMaster;
                
                //Restriccion de servicios facturados
                $servicios_facturados = DetalleAsignacion::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->where('status', 1)->count();
                
                if($servicios_facturados > 0) {
                    throw new Exception("Accion no permitida.! Existen servicios sin facturar, debera realizar la facturacion antes de realizar el cierre diario", 401); 
                }
                //Fin restriccion de servicios facturados
                $cierre->save();

                if($cierre->save()) {
                    LogController::log(Auth::user()->id, 'cierre diario','El usuario ejecuto el cierre de turno', $response = null);
                }

                /** Notificacion para el usuario cuando su servicio fue anulado */
                $type = 'cierre_diario';
                $correo = env('CEO');
                $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

                $mailData = [
                        'tasa_bcv'              => $tasa,
                        'clientes_atendidos'    => VentaServicio::where('fecha_venta', date('d-m-Y'))->count(),
                        'servicios_clientes'    => DetalleAsignacion::where('fecha', date('d-m-Y'))->count(),
                        'total_ventas'          => $cierre->total_ventas,
                        'total_dolares'         => VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_usd'),
                        'zelle'                 => $cierre->total_dolares_zelle,
                        'total_bolivares'       => $cierre->total_bolivares,
                        'ref_debito'            => $cierre->ref_debito,
                        'ref_credito'           => $cierre->ref_credito,
                        'ref_visaMaster'        => $cierre->ref_visaMaster,
                        'monto_ref_debito'      => $monto_ref_debito,
                        'monto_ref_credito'     => $monto_ref_credito,
                        'monto_ref_visaMaster'  => $monto_ref_visaMaster,
                        'conversion'            => $cierre->total_bolivares / $tasa,
                        'efectivo_caja_usd'     => $cierre->total_dolares_efectivo,
                        'fecha'                 => $cierre->created_at,
                        'user_email'            => $correo,
                        'responsable'           => $cierre->responsable,
                    ];

                NotificacionesController::notification($mailData, $type);

                Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-m-shield-check')
                    ->iconColor('success')
                    ->color('success')
                    ->body('El Cierre Diario fue realizado con éxito')
                    ->send();

            }

        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
}