<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DetalleAuditoriaProducto;
use Filament\Notifications\Notification;

class DetalleAuditoriaProductoController extends Controller
{
    static function crearDetalle($productos, $auditoria_id) {
        // dd($productos);
        try {

            $transaction = DB::transaction(function () use ($productos, $auditoria_id) {

                for ($i = 0; $i < count($productos); $i++) {
                    $detalle = new DetalleAuditoriaProducto();
                    $detalle->auditoria_inventario_id = $auditoria_id;
                    $detalle->producto_id = $productos[$i]['producto_id'];
                    $detalle->cantidad = $productos[$i]['cantidad'];  
                    $detalle->save();  
                }

            });

            return [
                'success' => true,
                'message' => 'La Auditoria de inventario fue creada correctamente.',
            ];
            
        } catch (\Throwable $th) {
            DB::rollBack();
            LogController::log(Auth::user()->id, 'excepcion-AuditoriaInventarioController(crearAuditoria)', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion: AuditoriaInventarioController::crearAuditoria() ')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
        
    }
}