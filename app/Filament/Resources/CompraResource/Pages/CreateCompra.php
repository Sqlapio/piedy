<?php

namespace App\Filament\Resources\CompraResource\Pages;

use Filament\Actions;
use App\Models\ResumenContable;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Filament\Resources\CompraResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompra extends CreateRecord
{
    protected static string $resource = CompraResource::class;

    protected function afterCreate(): void
    {
        //Creamos la entrada de inventario
        $entrada = new ResumenContable();
        $entrada->sucursal_id       = $this->data['sucursal_id'];
        $entrada->codigo            = $this->data['cod_compra'];
        $entrada->tipo              = 'compra';
        $entrada->monto_usd         = $this->data['monto_usd'] > 0 ? $this->data['monto_usd'] : 0.00;
        $entrada->monto_bsd         = $this->data['monto_bsd'] > 0 ? $this->data['monto_bsd'] : 0.00;
        $entrada->tasa_bcv          = $this->data['tasa_bcv'];
        $entrada->conversion        = $this->data['monto_bsd'] > 0 ? $this->data['monto_bsd'] / $this->data['tasa_bcv'] : 0.00;
        $entrada->total_operacion   = $entrada->conversion + $this->data['monto_usd'];
        $entrada->responsable       = $this->data['responsable'];
        $entrada->fecha             = $this->data['fecha_compra'];
        $entrada->descripcion       = $this->data['descripcion'];
        $entrada->save();

    }
}