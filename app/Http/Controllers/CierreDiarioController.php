<?php

namespace App\Http\Controllers;

use App\Models\CajaChica;
use App\Models\CierreDiario;
use App\Models\DetalleAsignacion;
use App\Models\Gasto;
use App\Models\TasaBcv;
use App\Models\VentaServicio;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CierreDiarioController extends Controller
{
    public static function cierreDiario($ref_debito, $monto_ref_debito, $ref_credito, $monto_ref_credito, $ref_visaMaster, $monto_ref_visaMaster)
    {
// dd($ref_debito, $monto_ref_debito, $ref_credito, $monto_ref_credito, $ref_visaMaster, $monto_ref_visaMaster);
        try {

            $query = CierreDiario::where('fecha', date('d-m-Y'))->count();

            if($query >= 2){
                throw new Exception("Usted no puede relizar mas de 2 cierres al dia. Por favor comuniquese con el administrador", 401);

            }else{

                /** Responsable del cierre */
                $user = Auth::user();

                /** totales en la tabla de ventas */
                $total_venta = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('total_USD');

                /** totales de pagos en Dolares*/
                $total_efectivo_usd = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Efectivo Usd')->sum('pago_usd');
                $total_zelle = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Zelle')->sum('pago_usd');

                /** totales de pagos en Bolivares*/
                $total_bs = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

                /** totales gastos en Dolares*/
                $efectivo_caja_usd = CajaChica::where('fecha', date('d-m-Y'))->first();

                /**Logica para evitar que una persona haga mas de dos cierres en el dia */
                $existe_cierre = CierreDiario::where('fecha', date('d-m-Y'))->where('responsable', $user->name)->first();

                if(isset($existe_cierre)){
                    throw new Exception("Accion no permitida.! Este usuario ya realizo un cierre diario. Por favor comuniquese con el administrador", 401);
                }

                $cierre = new CierreDiario();
                $cierre->total_ventas            = $total_venta;
                $cierre->total_dolares_efectivo  = $total_efectivo_usd;
                $cierre->total_dolares_zelle     = $total_zelle;
                $cierre->total_bolivares         = $total_bs;
                $cierre->ref_debito              = $ref_debito;
                $cierre->monto_ref_debito        = (str_replace(',', '.', str_replace('.', '', $monto_ref_debito))) == null ? 0.00 : str_replace(',', '.', str_replace('.', '', $monto_ref_debito));
                $cierre->ref_credito             = $ref_credito;
                $cierre->monto_ref_credito       = (str_replace(',', '.', str_replace('.', '', $monto_ref_credito))) == null ? 0.00 : str_replace(',', '.', str_replace('.', '', $monto_ref_credito));
                $cierre->ref_visaMaster          = $ref_visaMaster;
                $cierre->monto_ref_visaMaster    = (str_replace(',', '.', str_replace('.', '', $monto_ref_visaMaster))) == null ? 0.00 : str_replace(',', '.', str_replace('.', '', $monto_ref_visaMaster));
                $cierre->saldo_caja_chica        = (isset($efectivo_caja_usd->saldo)) ? $efectivo_caja_usd->saldo : 0;
                $cierre->fecha                   = date('d-m-Y');
                $cierre->responsable             = $user->name;
                $cierre->save();

                /** Notificacion para el usuario cuando su servicio fue anulado */
                $type = 'cierre_diario';
                $correo = env('CEO');
                $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

                $mailData = [
                        'tasa_bcv' => $tasa,
                        'clientes_atendidos' => VentaServicio::where('fecha_venta', date('d-m-Y'))->count(),
                        'servicios_clientes' => DetalleAsignacion::where('fecha', date('d-m-Y'))->count(),
                        'total_ventas' => $cierre->total_ventas,
                        'total_dolares' => VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_usd'),
                        'zelle' => $cierre->total_dolares_zelle,
                        'total_bolivares' => $cierre->total_bolivares,
                        'ref_debito' => $cierre->ref_debito,
                        'ref_credito' => $cierre->ref_credito,
                        'ref_visaMaster' => $cierre->ref_visaMaster,
                        'monto_ref_debito' => $monto_ref_debito,
                        'monto_ref_credito' => $monto_ref_credito,
                        'monto_ref_visaMaster' => $monto_ref_visaMaster,
                        'conversion' => $cierre->total_bolivares / $tasa,
                        'efectivo_caja_usd' => $cierre->total_dolares_efectivo,
                        'efectivo_caja_chica' => $cierre->saldo_caja_chica,
                        'fecha' => $cierre->created_at,
                        'user_email' => $correo,
                        'responsable' => $cierre->responsable,
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
