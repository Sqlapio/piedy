<?php

namespace App\Filament\Resources\GastoResource\Pages;

use App\Models\TasaBcv;
use App\Models\ResumenContable;
use App\Filament\Resources\GastoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGasto extends CreateRecord
{
    protected static string $resource = GastoResource::class;

    protected function afterCreate(): void
    {
        //Creamos la entrada de inventario
        $entrada = new ResumenContable();
        $entrada->sucursal_id       = $this->data['sucursal_id'] ?? $this->data['almacen_id'];
        $entrada->sucursal_id       = $this->data['almacen_id'] ?? $this->data['sucursal_id'];
        $entrada->codigo            = $this->data['numero_factura_gasto'];
        $entrada->tipo              = 'gasto';
        $entrada->monto_usd         = $this->data['monto_usd'] > 0 ? $this->data['monto_usd'] : 0.00;
        $entrada->monto_bsd         = $this->data['monto_bsd'] > 0 ? $this->data['monto_bsd'] : 0.00;
        $entrada->tasa_bcv          = $this->data['tasa_bcv'] ?? TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;
        $entrada->conversion        = $this->data['monto_bsd'] > 0 ? $this->data['monto_bsd'] / $this->data['tasa_bcv'] : 0.00;
        $entrada->total_operacion   = $entrada->conversion + $this->data['monto_usd'];
        $entrada->responsable       = $this->data['responsable'];
        $entrada->fecha             = $this->data['fecha_factura'];
        $entrada->descripcion       = $this->data['descripcion'];
        $entrada->save();

    }
}