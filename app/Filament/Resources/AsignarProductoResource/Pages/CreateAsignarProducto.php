<?php

namespace App\Filament\Resources\AsignarProductoResource\Pages;

use App\Filament\Resources\AsignarProductoResource;
use App\Models\AsignarProducto;
use App\Models\InventarioSucursal;
use Exception;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

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
        }

        $this->halt();

    }
}
