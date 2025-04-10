<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DetalleAuditoriaServicio;
use Filament\Notifications\Notification;

class DetalleAuditoriaServicioController extends Controller
{
    static function crearDetalle($productos, $auditoria_id) {
        try {

            $transaction = DB::transaction(function () use ($productos, $auditoria_id) {

                for ($i = 0; $i < count($productos); $i++) {
                    $detalle = new DetalleAuditoriaServicio();
                    $detalle->auditoria_inventario_id = $auditoria_id;
                    $detalle->servicio = $productos[$i]['descripcion'];
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
                ->title('Notificacion: AuditoriaInventarioController::crearAuditoria()')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }
}