<?php

namespace App\Livewire;

use App\Models\CierreDiario as ModelsCierreDiario;
use App\Models\FacturaMultiple;
use App\Models\Gasto;
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use WireUi\Traits\Actions;

class CierreDiario extends Component implements HasForms, HasTable
{
    use Actions;
    use InteractsWithTable;
    use InteractsWithForms;

    public $observaciones;

    public function notificacion_cierre()
    {
        $this->dialog()->confirm([

                'title'       => 'Notificación de sistema',
                'description' => 'Usted se dispone a realizar un cierre de caja. Esta acción totaliza los movimientos generados hasta la fecha y hora en curso.',
                'icon'        => 'warning',
                'accept'      => [
                    'label'  => 'Si, realizar cierre',
                    'method' => 'cierre_caja',
                    'params' => 'Saved',
                ],
                'reject' => [
                    'label'  => 'No, cancelar',
                    'method' => 'cancelar',

                ],

            ]);
    }

    public function cancelar()
    {
        dd('cancelado');
    }

    public function cierre_caja()
    {

        try {

            /** Responsable del cierre */
            $user = Auth::user();

            /** totales en la tabla de ventas */
            $total_venta  = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('total_USD');
            $total_pagos_usd   = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_usd');
            $total_pagos_bsd   = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

            /** totales en facturas multiples */
            $fm_pagos_usd      = FacturaMultiple::where('fecha_venta', date('d-m-Y'))->sum('pago_usd');
            $fm_pagos_bsd      = FacturaMultiple::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

            /** totales en gastos */
            $gastos_pagos_usd      = Gasto::where('fecha', date('d-m-Y'))->sum('monto_usd');
            $gastos_pagos_bsd      = Gasto::where('fecha', date('d-m-Y'))->sum('monto_bsd');

            /** Ventas */
            $venta_usd = $total_pagos_usd + $fm_pagos_usd;
            $venta_bsd = $total_pagos_bsd + $fm_pagos_bsd;

            /** Ventas netas */
            $venta_neta_usd = $venta_usd - $gastos_pagos_usd;
            $venta_neta_bsd = $venta_bsd - $gastos_pagos_bsd;

            /** totales en gastos */

            Debugbar::info('venta neta', $total_venta);
            Debugbar::info('pagos en dolares', $total_pagos_usd);
            Debugbar::info('pagos en bolivares', $total_pagos_bsd);
            Debugbar::info('pagos en dolares FM', $fm_pagos_usd);
            Debugbar::info('pagos en bolivares FM', $fm_pagos_bsd);

            Debugbar::info('total dolares', $venta_neta_usd);
            Debugbar::info('total bolivares', $venta_neta_bsd);

            $cierre = new ModelsCierreDiario();
            $cierre->total_pago_usd = $venta_usd;
            $cierre->total_pago_bsd = $venta_bsd;
            $cierre->total_gastos_pago_usd = $gastos_pagos_usd;
            $cierre->total_gastos_pago_bsd = $gastos_pagos_bsd;
            $cierre->venta_neta_usd = $venta_neta_usd;
            $cierre->venta_neta_bsd = $venta_neta_bsd;
            $cierre->fecha = date('d-m-Y');
            $cierre->responsable = $user->name;
            $cierre->observaciones = $this->observaciones;
            $cierre->save();

            $this->redirect('/cierre/diario');


        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ModelsCierreDiario::query())
            ->columns([
                TextColumn::make('total_pago_usd')
                ->sortable()
                ->searchable(),
                TextColumn::make('total_pago_bsd')
                ->sortable()
                ->searchable(),
                TextColumn::make('created_at')
                ->sortable()
                ->searchable(),
                TextColumn::make('responsable')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('observaciones')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                'responsable',
            ])
            ->filters([
                DateRangeFilter::make('created_at')->timezone('America/Caracas'),
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function inicio(){
        redirect()->to('/dashboard');
    }

    public function citas(){
        redirect()->to('/citas');
    }

    public function clientes(){
        redirect()->to('/clientes');
    }

    public function cabinas(){
        redirect()->to('/cabinas');
    }

    public function productos(){
        redirect()->to('/productos');
    }

    public function servicios(){
        redirect()->to('/servicios');
    }

    public function atras(){
        redirect()->to('/dashboard');
    }


    public function render()
    {
        return view('livewire.cierre-diario');
    }
}
