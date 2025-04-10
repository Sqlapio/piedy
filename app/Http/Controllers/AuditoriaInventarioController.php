<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AuditoriaInventario;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class AuditoriaInventarioController extends Controller
{
    static function crearAuditoria($desde, $hasta, $requisiciones){
        // dd($requisiciones);
        try {

            $requisiciones_array = [];
            
            for ($i = 0; $i < count($requisiciones); $i++) {
                $requisiciones_array[$i] = $requisiciones[$i]['codigo'];
                
            }

            $transaction = DB::transaction(function () use ($desde, $hasta, $requisiciones_array) {
                $auditoria = new AuditoriaInventario();
                $auditoria->codigo_auditoria = 'AUD-' .rand(11111, 99999);
                $auditoria->desde = $desde;
                $auditoria->hasta = $hasta;
                $auditoria->requisiciones = $requisiciones_array;
                $auditoria->responsable = Auth::user()->name;
                $auditoria->save();

                return $auditoria;
            });

            return [
                'auditoria_id' => $transaction->id,
                'success' => true,
                'message' => 'La Auditoria fue creada correctamente, por favor dirijase al apartado de auditorias de inventario para ver el detalle.',
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