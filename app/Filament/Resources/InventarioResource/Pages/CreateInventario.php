<?php

namespace App\Filament\Resources\InventarioResource\Pages;

use App\Filament\Resources\InventarioResource;
use Filament\Actions;
use App\Models\EntradaInventario;
use App\Models\Inventario;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class CreateInventario extends CreateRecord
{
    protected static string $resource = InventarioResource::class;

    protected function beforeCreate(): void
    {
        $producto = Inventario::where('producto_id', $this->data['producto_id'])
        ->where('almacen_id',$this->data['almacen_id'])
        ->get();
        
        if($producto->count() > 0)
        {
            Notification::make()
            ->warning()
            ->title('Notificacion')
            ->body('El producto ya se encuentra en el inventario. Para cargar existencia debe realizar una reposicion')
            // ->persistent(
            // ->actions([
            //     Action::make('subscribe')
            //         ->button()
            //         ->url(route('dashboard'), shouldOpenInNewTab: true),
            // ])
            ->send();
    
            $this->halt();
        }
    }

    protected function afterCreate(): void
    {
        //Creamos la entrada de inventario
        $entrada = new EntradaInventario();
        $entrada->cod_movimiento    = 'Psi-'.random_int(11111, 99999);
        $entrada->almacen_id        = $this->data['almacen_id'];
        $entrada->producto_id       = $this->data['producto_id'];
        $entrada->cantidad          = $this->data['cantidad'];
        $entrada->tipo_movimiento   = 'primera carga';
        $entrada->responsable       = auth()->user()->name;
        $entrada->save();

    }
}