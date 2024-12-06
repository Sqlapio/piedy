<?php

namespace App\Http\Controllers;

use App\Models\TasaBcv;
use App\Models\Disponible;
use App\Models\FacturacionMultiple;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class FacturacionMultipleController extends Controller
{
    static function totalizar_fac_multiple($records)
    {
        try {

            
            $array_costos_usd = [];
            $array_codigos = [];
            
            $tasa_bcv = TasaBcv::first()->tasa;
            
            for($i = 0; $i < count($records); $i++)
            {
                array_push($array_codigos, $records[$i]->cod_asignacion);
                array_push($array_costos_usd, $records[$i]->venta_total);

                $update_status = Disponible::where('sucursal_id', Auth::user()->sucursal_id)
                ->where('cod_asignacion', $records[$i]->cod_asignacion)
                ->where('status', 'cerrado')
                ->where('status_fac_multiple', 1)
                ->first();
                
                $update_status->status_fac_multiple = 2;
                $update_status->save();
            }

            
            $factura = new FacturacionMultiple();
            $factura->cod_fac_multiple  = 'PFm-'.random_int(11111111, 99999999);
            $factura->cod_asignacion    = json_encode($array_codigos);
            $factura->venta_total_usd   = array_sum($array_costos_usd);
            $factura->venta_total_bsd   = array_sum($array_costos_usd) * $tasa_bcv;
            $factura->sucursal_id       = Auth::user()->sucursal_id;
            $factura->save();

            if($factura->save()){
                Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-m-shield-check')
                ->iconColor('success')
                ->color('success')
                ->body('Totalizacion de servicio correcta!!!')
                ->send();

                session(['cod_asignacion_fm' => $factura->cod_fac_multiple]);
                // $codigo = $request->session()->get('cod_asignacion');

                return true;
            }

            //code...
        } catch (\Throwable $th) {
            dd($th);
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