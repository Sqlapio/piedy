<?php

namespace App\Filament\Resources\GastoResource\Pages;

use App\Models\Gasto;
use App\Models\TasaBcv;
use App\Models\Proveedor;
use App\Models\LibroCompra;
use App\Models\ResumenContable;
use App\Models\ConfiguracionNomina;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Filament\Resources\GastoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGasto extends CreateRecord
{
    protected static string $resource = GastoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('REGISTRO DE COMPRA/GASTO')
            ->body('El registro fue creado con exito.');
    }

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

        //Creamos el asiento en el libro de compras
        if($this->data['feedback'] == "1") {
            
            $info_proveedor = Proveedor::where('id', $this->data['proveedor_id'])->first();
            $last_gasto = Gasto::latest()->first();
            
            $libroCompra = new LibroCompra();
            $libroCompra->gasto_id                  = $last_gasto->id;
            $libroCompra->sucursal_id               = $this->data['sucursal_id'];
            $libroCompra->fecha_documento           = $this->data['fecha_factura'];
            $libroCompra->rif                       = $info_proveedor->rif;
            $libroCompra->razon_social              = $info_proveedor->nombre;
            $libroCompra->nro_documento             = $this->data['numero_factura_gasto'];
            $libroCompra->nro_control               = $this->data['nro_control'];
            $libroCompra->tipo_transaccion          = 'Registro';
            $libroCompra->total_comp_con_iva        = $this->data['total_gasto_bsd'];
            $libroCompra->base_imponible_internas   = $this->data['monto_bsd'];
            $libroCompra->porcen_alicuota_internas  = ConfiguracionNomina::first()->iva * 100;
            $libroCompra->impuesto_iva_internas     = $this->data['iva'];
            $libroCompra->responsable               = Auth::user()->name;
            $libroCompra->save();
            
            if($libroCompra->save())
            {
                Notification::make()
                    ->success()
                    ->title('REGISTRO LIBRO DE COMPRA')
                    ->body('El asiento8fue creado con exito.')
                    ->send();
            }
        }
        


    }
}