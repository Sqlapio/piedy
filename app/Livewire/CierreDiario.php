<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Models\CajaChica;
use App\Models\CierreDiario as ModelsCierreDiario;
use App\Models\FacturaMultiple;
use App\Models\Gasto;
use App\Models\TasaBcv;
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

            /** totales de pagos en Dolares*/
            $total_efectivo_usd = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Efectivo Usd')->sum('pago_usd');
            $total_zelle = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Zelle')->sum('pago_usd');

            /** totales de pagos en Bolivares*/
            $total_bs = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

            /** totales gastos en Dolares*/
            $total_gastos_usd = Gasto::where('fecha', date('d-m-Y'))->sum('monto_usd');

            /** totales gastos en Dolares*/
            $efectivo_caja_usd = CajaChica::where('fecha', date('d-m-Y'))->first()->saldo;


            $cierre = new ModelsCierreDiario();
            $cierre->total_ventas            = $total_venta;
            $cierre->total_dolares_efectivo  = $total_efectivo_usd;
            $cierre->total_dolares_zelle     = $total_zelle;
            $cierre->total_bolivares         = $total_bs;
            $cierre->total_gastos            = $total_gastos_usd;
            $cierre->saldo_caja_chica        = $efectivo_caja_usd;
            $cierre->fecha = date('d-m-Y');
            $cierre->responsable = $user->name;
            $cierre->save();

            /** Notificacion para el usuario cuando su servicio fue anulado */
            $type = 'cierre_diario';
            $correo = env('CEO');
            $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

            $mailData = [
                    'tasa_bcv' => $tasa,
                    'total_ventas' => $cierre->total_ventas,
                    'total_dolares' => VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_usd'),
                    'zelle' => $cierre->total_dolares_zelle,
                    'total_bolivares' => $cierre->total_bolivares,
                    'conversion' => $cierre->total_bolivares / $tasa,
                    'efectivo_caja_usd' => $cierre->total_dolares_efectivo,
                    'gastos' => $cierre->total_gastos,
                    'efectivo_caja_chica' => $cierre->saldo_caja_chica,
                    'fecha' => $cierre->created_at,
                    'user_email' => $correo,
                    'responsable' => $cierre->responsable,
                ];

            NotificacionesController::notification($mailData, $type);

            sleep(1);

            $this->dialog()->success(
                $title = 'NOTIFICACION !!!',
                $description = 'El cierre de caja se ha realizado de forma exitósa.'
            );

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ModelsCierreDiario::query()->whereDate('created_at', now()->toDateString()))
            ->columns([
                TextColumn::make('total_ventas')
                ->money('USD')
                ->label('Venta Total($)')
                ->sortable()
                ->searchable(),
                TextColumn::make('total_dolares_efectivo')
                ->money('USD')
                ->label('Efectivo($) en Tienda')
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
                ->money('USD')
                ->label(_('Gastos($)'))
                ->sortable()
                ->searchable(),
                TextColumn::make('saldo_caja_chica')
                ->money('USD')
                ->label(_('Efectivo($) Caja Chica'))
                ->sortable()
                ->searchable(),
                TextColumn::make('created_at')
                ->label(_('Fecha de cierre'))
                ->sortable()
                ->searchable(),
                TextColumn::make('responsable')
                ->sortable()
                ->searchable(),
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
