<?php

namespace App\Filament\Resources\ProductoResource\Pages;

use Filament\Actions;
use App\Models\DetalleRequisicion;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductoResource;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Auth;

class EditProducto extends EditRecord
{
    protected static string $resource = ProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function beforeSave(): void
    // {
    //     if($this->data['max'] == $this->data['min'])
    //     {
    //         Notification::make()
    //         ->warning()
    //         ->title('Notificacion')
    //         ->body('La cantidad maxima y minima de existencia del producto no pueden ser iguales')
    //         ->send();

    //         $this->halt();
    //     }
    // }

    protected function afterSave(): void
    {
        try {

            //Buscamos si el producto se encuentra activo en el un requisicion y editamos su informacion
            $requisicion = DetalleRequisicion::where('producto_id', $this->data['id'])->where('status_id', 5)->first();

            if (isset($requisicion)) {
                $requisicion->costo      = $this->data['precio_venta'];
                $requisicion->contenido  = $this->data['contenido_neto'] . '' . $this->data['unidad'];
                $requisicion->uso        = $this->data['uso'];
                $requisicion->sub_total   = $requisicion->cantidad * $requisicion->costo;
                $requisicion->save();

                Notification::make()
                ->success()
                ->title('Actualizacion de Requisicion')
                ->body('La requisicion: ' . $requisicion->codigo . 'fue actualizada con exito ');
            }

        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-EditProducto(afterSave)', $th->getMessage(), $response = null);
        }

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('Producto editado')
        ->body('El producto fue editado con exito.');
    }


}
