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

            /** Total pagos en dolares efectivo */
            $total_pagos_usd = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Efectivo Usd')->sum('pago_usd');
            $total_pagos_usd_multiple = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Multiple')->sum('pago_usd');

            /** Total pagos en dolares zelle */
            $total_pagos_usd_zelle = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Zelle')->sum('pago_usd');

            /** Total pagos en bolivares */
            $total_pagos_bsd = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

            /** totales en gastos */
            $gastos_pagos_usd = Gasto::where('fecha', date('d-m-Y'))->sum('monto_usd');

            /** Total en Bolivares */
            $total_bs = $total_pagos_bsd;

            /** Total en Dolares Efectivo */
            $total_usd = $total_pagos_usd + $total_pagos_usd_multiple;

            /** Total en Dolares Zelle */
            $total_usd_zelle = $total_pagos_usd_zelle;

            /** Total de gastos */
            $total_gastos = $gastos_pagos_usd;

            /** Venta Total en dolares */
            $venta_total_usd = $total_usd + $total_usd_zelle;

            /** Venta Neta en dolares */
            $venta_neta_usd = $total_usd - $total_gastos;

            /** Venta Neta en bolivares */
            $venta_neta_bsd = $total_pagos_bsd;


            $cierre = new ModelsCierreDiario();
            $cierre->total_dolares_efectivo  = $total_usd;
            $cierre->total_dolares_zelle     = $total_usd_zelle;
            $cierre->total_bolivares         = $total_bs;
            $cierre->total_gastos            = $total_gastos;
            $cierre->venta_neta_dolares      = $venta_neta_usd;
            $cierre->venta_neta_bolivares    = $venta_neta_bsd;
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
            ->query(ModelsCierreDiario::query()->orderby('id', 'desc'))
            ->columns([
                TextColumn::make('total_dolares_efectivo')
                ->money('USD')
                ->label(_('Efectivo($)'))
                ->sortable()
                ->searchable(),
                TextColumn::make('total_dolares_zelle')
                ->money('USD')
                ->label(_('Zelle($)'))
                ->sortable()
                ->searchable(),
                TextColumn::make('total_bolivares')
                ->money('VES')
                ->label(_('Bolivares(Bs)'))
                ->sortable()
                ->searchable(),
                TextColumn::make('total_gastos')
                ->label(_('Gastos'))
                ->sortable()
                ->searchable(),
                TextColumn::make('venta_neta_dolares')
                ->money('USD')
                ->label(_('Efectivo($) en caja'))
                ->sortable()
                ->searchable(),
                TextColumn::make('created_at')
                ->label(_('Fecha de cierre'))
                ->sortable()
                ->searchable(),
                TextColumn::make('responsable')
                ->sortable()
                ->searchable(),
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
