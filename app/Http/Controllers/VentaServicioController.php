<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\TasaBcv;
use App\Models\MetodoPago;
use Illuminate\Http\Request;
use App\Models\VentaServicio;
use App\Models\ConfiguracionNomina;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class VentaServicioController extends Controller
{
    static function venta_servicio_usd($cod_asignacion, $costo_total_servicios, $comision_total, $comision_gerente, $info_serv_empleado, $ref_zelle, $propina_usd, $propina_bsd, $pro_ref_debito_credito, $pro_nro_tarjeta, $metodo_pago){
      
        try {

            $descuento = ConfiguracionNomina::select('iva_nomina', 'igtf')->first(); // IVA = 1.16

            $facturar = new VentaServicio();
            $facturar->cod_asignacion           = $cod_asignacion;
            $facturar->metodo_pago              = MetodoPago::find($metodo_pago)->descripcion;
            $facturar->total_USD                = $costo_total_servicios;
            $facturar->pago_usd                 = $costo_total_servicios;
            $facturar->base_imponible_usd       = $costo_total_servicios / 1.19;
            $facturar->iva_usd                  = $facturar->base_imponible_usd * 0.16 ?? 0.00;
            $facturar->impuesto_usd            = $facturar->base_imponible_usd * 0.03 ?? 0.00;
            $facturar->comision_dolares         = $comision_total / 1.19;
            $facturar->comision_gerente         = $comision_gerente;
            $facturar->empleado_id              = $info_serv_empleado->empleado_id;
            $facturar->cliente_id               = $info_serv_empleado->cliente_id;
            $facturar->fecha_venta              = now()->format('d-m-Y');
            $facturar->responsable_id           = Auth::user()->id;
            $facturar->responsable              = Auth::user()->name;
            $facturar->ref_zelle                = isset($ref_zelle) ? $ref_zelle : 'N/A';
            $facturar->propina_usd              = isset($propina_usd) ? $propina_usd : 0.00;
            $facturar->propina_bsd              = isset($propina_bsd) ? $propina_bsd : 0.00;
            $facturar->pro_ref_debito_credito   = isset($pro_ref_debito_credito) ? $pro_ref_debito_credito : 'N/A';
            $facturar->pro_nro_tarjeta          = isset($pro_nro_tarjeta) ? $pro_nro_tarjeta : 'N/A';
            $facturar->servicios                = UtilsController::array_servicios($cod_asignacion);
            $facturar->sucursal_id              = Auth::user()->sucursal_id;
            $facturar->save();

        } catch (\Throwable $th) {
            dd($th);
            LogController::log(Auth::user()->id, 'excepcion-VentaServicioController(venta_servicio_usd)', $th->getMessage(), $response = null);

            Notification::make()
            ->title('Notificacion: VentaServicioController::venta_servicio_usd()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function venta_servicio_bsd($metodo_pago_dos, $cod_asignacion, $costo_total_servicios, $comision_total, $comision_gerente, $info_serv_empleado, $ref_pago_movil, $ref_debito_credito, $nro_tarjeta, $propina_usd, $propina_bsd, $pro_ref_debito_credito, $pro_nro_tarjeta){
        
        try {

            $tasa_bcv = TasaBcv::all()->first()->tasa;
            $descuento = ConfiguracionNomina::select('iva_nomina', 'igtf')->first(); // IVA = 1.16
            // dd($tasa_bcv, $iva);

            $facturar = new VentaServicio();
            $facturar->cod_asignacion           = $cod_asignacion;
            $facturar->metodo_pago_dos          = MetodoPago::find($metodo_pago_dos)->descripcion;
            $facturar->total_USD                = $costo_total_servicios;
            $facturar->pago_bsd                 = $costo_total_servicios * $tasa_bcv;
            $facturar->base_imponible_bsd       = $facturar->pago_bsd / $descuento->iva_nomina;
            $facturar->iva_bsd                  = $facturar->base_imponible_bsd * 0.16 ?? 0.00;
            $facturar->comision_bolivares       = $comision_total / $descuento->iva_nomina;
            $facturar->comision_gerente         = $comision_gerente; // IVA = 16%(1.16) + IGTF = 3%(1.03)
            $facturar->empleado_id              = $info_serv_empleado->empleado_id;
            $facturar->cliente_id               = $info_serv_empleado->cliente_id;
            $facturar->fecha_venta              = now()->format('d-m-Y');
            $facturar->responsable_id           = Auth::user()->id;
            $facturar->responsable              = Auth::user()->name;
            $facturar->ref_pago_movil           = isset($ref_pago_movil) ? $ref_pago_movil : 'N/A';
            $facturar->ref_debito_credito       = isset($ref_debito_credito) ? $ref_debito_credito : 'N/A';
            $facturar->nro_tarjeta              = isset($nro_tarjeta) ? $nro_tarjeta : 'N/A';
            $facturar->propina_usd              = isset($propina_usd) ? $propina_usd : 0.00;
            $facturar->propina_bsd              = isset($propina_bsd) ? $propina_bsd : 0.00;
            $facturar->pro_ref_debito_credito   = isset($pro_ref_debito_credito) ? $pro_ref_debito_credito : 'N/A';
            $facturar->pro_nro_tarjeta          = isset($pro_nro_tarjeta) ? $pro_nro_tarjeta : 'N/A';
            $facturar->servicios                = UtilsController::array_servicios($cod_asignacion);
            $facturar->sucursal_id              = Auth::user()->sucursal_id;
            $facturar->save();

        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-VentaServicioController(venta_servicio_bsd)', $th->getMessage(), $response = null);

            Notification::make()
            ->title('Notificacion: VentaServicioController::venta_servicio_bsd()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function venta_servicio_multiple($monto_usd, $monto_bsd, $metodo_pago, $metodo_pago_dos, $cod_asignacion, $total_servicios, $comision_dolares, $comision_bolivares, $comision_gerente, $ref_zelle, $ref_pago_movil, $ref_debito_credito, $nro_tarjeta, $info_serv_empleado, $propina_usd, $propina_bsd, $pro_ref_debito_credito, $pro_nro_tarjeta){
        
        try {

            $tasa_bcv = TasaBcv::all()->first()->tasa;
            $descuento = ConfiguracionNomina::select('iva_nomina', 'igtf')->first(); // IVA = 1.16

            $facturar = new VentaServicio();
            $facturar->cod_asignacion           = $cod_asignacion;
            $facturar->metodo_pago              = MetodoPago::find($metodo_pago)->descripcion;
            $facturar->metodo_pago_dos          = MetodoPago::find($metodo_pago_dos)->descripcion;
            $facturar->total_USD                = $total_servicios;
            $facturar->pago_usd                 = $monto_usd;

            //impuestos en USD
            $facturar->base_imponible_usd       = $monto_usd / 1.19;
            $facturar->iva_usd                  = $facturar->base_imponible_usd * 0.16 ?? 0.00;
            $facturar->impuesto_usd            = $facturar->base_imponible_usd * 0.03 ?? 0.00;
            
            //impuestos en VES
            $facturar->pago_bsd                 = $monto_bsd;
            $facturar->base_imponible_bsd       = $monto_bsd / $descuento->iva_nomina;
            $facturar->iva_bsd                  = $facturar->base_imponible_bsd * 0.16 ?? 0.00;
            
            //comisiones
            $facturar->comision_dolares         = $comision_dolares / 1.19; // IVA = 16%(1.16) + IGTF = 3%(1.03)
            $facturar->comision_bolivares       = $comision_bolivares / $descuento->iva_nomina;
            
            $facturar->comision_gerente         = $comision_gerente;
            $facturar->empleado_id              = $info_serv_empleado->empleado_id;
            $facturar->cliente_id               = $info_serv_empleado->cliente_id;
            $facturar->fecha_venta              = now()->format('d-m-Y');
            $facturar->responsable_id           = Auth::user()->id;
            $facturar->responsable              = Auth::user()->name;
            $facturar->ref_zelle                = isset($ref_zelle) ? $ref_zelle : 'N/A';
            $facturar->ref_pago_movil           = isset($ref_pago_movil) ? $ref_pago_movil : 'N/A';
            $facturar->ref_debito_credito       = isset($ref_debito_credito) ? $ref_debito_credito : 'N/A';
            $facturar->nro_tarjeta              = isset($nro_tarjeta) ? $nro_tarjeta : 'N/A';
            $facturar->propina_usd              = isset($propina_usd) ? $propina_usd : 0.00;
            $facturar->propina_bsd              = isset($propina_bsd) ? $propina_bsd : 0.00;
            $facturar->pro_ref_debito_credito   = isset($pro_ref_debito_credito) ? $pro_ref_debito_credito : 'N/A';
            $facturar->pro_nro_tarjeta          = isset($pro_nro_tarjeta) ? $pro_nro_tarjeta : 'N/A';
            $facturar->servicios                = UtilsController::array_servicios($cod_asignacion);
            $facturar->sucursal_id              = Auth::user()->sucursal_id;
            $facturar->save();

        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-VentaServicioController(venta_servicio_multiple)', $th->getMessage(), $response = null);

            Notification::make()
            ->title('Notificacion: VentaServicioController::venta_servicio_multiple()')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }
}