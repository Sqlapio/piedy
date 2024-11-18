<?php

namespace App\Http\Controllers;

use App\Models\Disponible;
use App\Models\MetodoPago;
use App\Models\TasaBcv;
use App\Models\Venta;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    static function venta($cod_asignacion, $total_venta, $metodo_pago, $metodo_pago_dos)
    {

        try {
            //code...
            $venta = new Venta();
            $venta->cod_asignacion          = $cod_asignacion;
            $venta->total_venta             = $total_venta;
            $venta->fecha                   = now()->format('d-m-Y');
            $venta->responsable             = Auth::user()->name;
            $venta->metodo_pago_dolares     = $metodo_pago != 'N/A' ? MetodoPago::find($metodo_pago)->descripcion : $metodo_pago;
            $venta->metodo_pago_bolivares   = $metodo_pago_dos != 'N/A' ? MetodoPago::find($metodo_pago_dos)->descripcion : $metodo_pago_dos;
            $venta->tasa_bcv                = TasaBcv::all()->first()->tasa;
            $venta->save();

            $servicio_disponible = Disponible::where('cod_asignacion', $cod_asignacion)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('status', 'cerrado')
            ->first();

            $servicio_disponible->status = 'facturado';
            $servicio_disponible->save();

        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: VentaController::venta() ')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function venta_producto($cod_asignacion, $total_venta, $metodo_pago, $metodo_pago_dos)
    {

        try {
            //code...
            $venta = new Venta();
            $venta->cod_asignacion          = $cod_asignacion;
            $venta->total_venta             = $total_venta;
            $venta->fecha                   = now()->format('d-m-Y');
            $venta->responsable             = Auth::user()->name;
            $venta->metodo_pago_dolares     = $metodo_pago != 'N/A' ? MetodoPago::find($metodo_pago)->descripcion : $metodo_pago;
            $venta->metodo_pago_bolivares   = $metodo_pago_dos != 'N/A' ? MetodoPago::find($metodo_pago_dos)->descripcion : $metodo_pago_dos;
            $venta->tasa_bcv                = TasaBcv::all()->first()->tasa;
            $venta->save();

        } catch (\Throwable $th) {
            Notification::make()
            ->title('Notificacion: VentaController::venta() ')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }
}
