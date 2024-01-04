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
        $this->redirect('/cierre/diario');
    }

    public function cierre_caja()
    {

        try {

            /** Responsable del cierre */
            $user = Auth::user();

            /** totales en la tabla de ventas */
            $total_venta = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('total_USD');
            $total_pagos_usd = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_usd');
            $total_pagos_bsd = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

            $total_pagos_ef_usd   = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Efectivo Usd')->sum('pago_usd');
            $total_pagos__ef_bsd  = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Efectivo Bsd')->sum('pago_bsd');
            $total_pagos_ze       = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Zelle')->sum('pago_usd');
            $total_pagos_pm       = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Pago movil')->sum('pago_bsd');
            $total_pagos_tr       = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Tranferencia')->sum('pago_bsd');
            $total_pagos_pv       = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Punto de venta')->sum('pago_bsd');

            /** totales en facturas multiples */
            $fm_pagos_usd = FacturaMultiple::where('fecha_venta', date('d-m-Y'))->sum('pago_usd');
            $fm_pagos_bsd = FacturaMultiple::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

            /** totales en gastos */
            $gastos_pagos_usd = Gasto::where('fecha', date('d-m-Y'))->sum('monto_usd');
            $gastos_pagos_bsd = Gasto::where('fecha', date('d-m-Y'))->sum('monto_bsd');

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
            $cierre->total_pagos_ef_usd = $total_pagos_ef_usd;
            $cierre->total_pagos_ef_bsd = $total_pagos__ef_bsd;
            $cierre->total_pagos_ze = $total_pagos_ze;
            $cierre->total_pagos_pm = $total_pagos_pm;
            $cierre->total_pagos_tr = $total_pagos_tr;
            $cierre->total_pagos_pv = $total_pagos_pv;
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

            sleep(1);

            $this->dialog()->success(
                $title = 'NOTIFICACION !!!',
                $description = 'El cierre de caja se ha realizado de forma exitósa.'
            );

        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ModelsCierreDiario::query())
            ->columns([
                TextColumn::make('total_pagos_ef_usd')
                ->label(_('Efectivo($)'))
                ->sortable()
                ->searchable(),
                TextColumn::make('total_pagos_ef_bsd')
                ->label(_('Efectivo(Bs)'))
                ->sortable()
                ->searchable(),
                TextColumn::make('total_pagos_ze')
                ->label(_('Zelle'))
                ->sortable()
                ->searchable(),
                TextColumn::make('total_pagos_pm')
                ->label(_('PagoMovil'))
                ->sortable()
                ->searchable(),
                TextColumn::make('total_pagos_tr')
                ->label(_('Transferencia'))
                ->sortable()
                ->searchable(),
                TextColumn::make('total_pagos_pv')
                ->label(_('Punto de Venta'))
                ->sortable()
                ->searchable(),
                TextColumn::make('total_pago_usd')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_pago_bsd')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                ->label(_('Fecha de cierre'))
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
