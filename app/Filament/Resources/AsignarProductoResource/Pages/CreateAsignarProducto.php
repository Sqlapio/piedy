<?php

namespace App\Filament\Resources\AsignarProductoResource\Pages;

use App\Filament\Resources\AsignarProductoResource;
use App\Http\Controllers\MovimientoInventarioController;
use App\Models\AsignarProducto;
use App\Models\InventarioSucursal;
use App\Models\LogInventario;
use App\Models\MovimientoInventario;
use Exception;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAsignarProducto extends CreateRecord
{
    protected static string $resource = AsignarProductoResource::class;

    protected function beforeCreate(): void
    {
        try {
            $existencia = InventarioSucursal::where('producto_id',$this->data['producto_id'])->first()->cantidad;
            if($existencia <= 0){
                throw new Exception("El producto tiene existencia cero(0). Reponga existencia y vuelva a intentar", 401);
            }
            //code...
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-c-x-circle')
                ->color('danger')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();

                $this->halt();
        }


    }

    protected function afterCreate(): void
    {
        try {

            //Resta de la existencia del producto de acuerdo a su sucursal
            $existencia = InventarioSucursal::where('producto_id',$this->data['producto_id'])->where('sucursal_id', $this->data['sucursal_id'])->first()->cantidad;
            $existencia -= $this->data['cantidad'];
            InventarioSucursal::where('producto_id',$this->data['producto_id'])->where('sucursal_id', $this->data['sucursal_id'])->update([
                'cantidad' => $existencia
            ]);

            //Registramos el movimiento de inventario

            MovimientoInventarioController::registrar_movimiento(
                $this->data['producto_id'],
                $this->data['cantidad'],
                $this->data['sucursal_id'],
                $codigo = 'Pae-'.random_int(11111111, 99999999),
                $tipoMovimiento = 'Asignación de producto',
            );

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
