<?php

namespace App\Filament\Supervisor\Resources\CierreDiarioResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Http\Controllers\CierreDiarioController;
use App\Filament\Supervisor\Resources\CierreDiarioResource;

class CreateCierreDiario extends CreateRecord
{
    protected static string $resource = CierreDiarioResource::class;

    // protected function beforeCreate(): void
    // {
    //     $cierre = CierreDiarioController::cierreDiario(
    //         $this->data['ref_debito'],
    //         $this->data['monto_ref_debito'],
    //         $this->data['ref_credito'],
    //         $this->data['monto_ref_credito'],
    //         $this->data['ref_visaMaster'],
    //         $this->data['monto_ref_visaMaster'],
    //         $this->data['observaciones']
    //     );

    //     if ($cierre == true) {
    //         Notification::make()
    //             ->title('Cierre creado con exito')
    //             ->success()
    //             ->send();
    //         $this->halt();
    //     }

    //     if ($cierre == false) {
    //         $this->halt();
    //     }
    // }
}

//$ref_debito, $monto_ref_debito, $ref_credito, $monto_ref_credito, $ref_visaMaster, $monto_ref_visaMaster, $observaciones = null