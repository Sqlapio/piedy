<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Producto;
use App\Models\Requisicion;
use Illuminate\Http\Request;
use App\Models\DetalleRequisicion;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class RequisicionController extends Controller
{
    static function crearRequisicion($data)
    {
        try {

            $requisicion = Requisicion::create([
                'codigo'        => $data['codigo'],
                'sucursal_id'   => Auth::user()->sucursal_id,
                'user_id'       => Auth::user()->id,
                'fecha'         => now()->format('d-m-Y'),
            ]);

            if(isset($requisicion)) {
                
                for ($i = 0; $i < count($data['productos']); $i++) {
                    $info_producto = Producto::where('id', $data['productos'][$i]['producto_id'])->first();
                    DetalleRequisicion::create([
                        'codigo'         => $data['codigo'],
                        'requisicion_id' => $requisicion->id,
                        'producto_id'    => $data['productos'][$i]['producto_id'],
                        'sucursal_id'    => Auth::user()->sucursal_id,
                        'cantidad'       => $data['productos'][$i]['cantidad'],
                        'uso'            => $info_producto->uso,
                    ]);
                }
            }
            
            return true;
            
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-RequisicionController(crearRequisicion)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    static function detalleRequisicion($codigo, $sucursal_id)
    {
        try {

            $detalle = Requisicion::where('codigo', $codigo)->where('sucursal_id', $sucursal_id)->with('sucursal')->first();
            $responsable = User::where('id', $detalle->user_id)->first()->name;
            return view('detalle-requisicion', compact('codigo', 'sucursal_id', 'detalle', 'responsable'));
        } catch (\Throwable $th) {
            // dd($th);
            LogController::log(1, 'excepcion(detalleRequisicion Link externo)', $th->getMessage(), $response = null);
        }
    }
}