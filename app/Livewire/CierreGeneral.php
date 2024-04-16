<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Models\CierreDiario;
use App\Models\CierreGeneral as ModelsCierreGeneral;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Symfony\Component\Finder\Iterator\DateRangeFilterIterator;
use WireUi\Traits\Actions;

class CierreGeneral extends Component implements HasForms, HasTable
{
    use Actions;
    use InteractsWithTable;
    use InteractsWithForms;

    public function notificacion_cierre()
    {
        $this->dialog()->confirm([

                'title'       => 'Notificación de sistema',
                'description' => 'Usted se dispone a realizar un cierre general. Esta acción totaliza los movimientos generados y almacenados en los procesos de cierres diarios, del mes en curso.',
                'icon'        => 'warning',
                'accept'      => [
                    'label'  => 'Si, realizar cierre',
                    'method' => 'cierre_general',
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
        $this->redirect('/cierre/general');
    }

    public function cierre_general()
    {

        try {

            $ultimo_cierre = ModelsCierreGeneral::latest()->first();

            $fecha_siguiente =  date("Y-m-d", strtotime(date($ultimo_cierre->fecha) . "+1 day"));

            $movimientos = DB::table('cierre_diarios')
                ->select
                    (
                        DB::raw('SUM(total_ventas) as total_ventas'),
                        DB::raw('SUM(total_dolares_efectivo) as total_dolares_efectivo'),
                        DB::raw('SUM(total_dolares_zelle) as total_dolares_zelle'),
                        DB::raw('SUM(total_bolivares) as total_bolivares'),
                        DB::raw('SUM(total_gastos) as total_gastos')
                    )
                // ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->whereBetween('created_at', [$fecha_siguiente.' 00:00:00' , now()->endOfMonth()])
                ->get();

            $res = json_decode(json_encode($movimientos), true);

            /** Responsable del cierre */
            $user = Auth::user();

            $cierre = new ModelsCierreGeneral();
            $cierre->total_ventas            = $res[0]['total_ventas'];
            $cierre->total_dolares_efectivo  = $res[0]['total_dolares_efectivo'];
            $cierre->total_dolares_zelle     = $res[0]['total_dolares_zelle'];
            $cierre->total_bolivares         = $res[0]['total_bolivares'] + $ultimo_cierre->total_bolivares;
            $cierre->fecha = date('d-m-Y');
            $cierre->responsable = $user->name;
            $cierre->save();

            /** Notificacion para el usuario cuando su servicio fue anulado */
            $type = 'cierre_general';
            $correo = env('CEO');
            $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

            $mailData = [
                    'tasa_bcv' => $tasa,
                    'total_ventas' => $cierre->total_ventas,
                    'total_dolares_efectivo' => $cierre->total_dolares_efectivo,
                    'total_dolares_zelle' => $cierre->total_dolares_zelle,
                    'total_bolivares' => $cierre->total_bolivares,
                    'user_email' => $correo,
                ];

            NotificacionesController::notification($mailData, $type);

            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->iconColor('success')
            ->body('El Cierre General se realizo con éxito')
            ->send();

            return redirect()->route('cierre_general');


        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CierreDiario::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
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
                ->label('Zelle($)')
                ->sortable()
                ->searchable(),
                TextColumn::make('total_bolivares')
                ->money('VES')
                ->label('Bolivares(Bs)')
                ->sortable()
                ->searchable(),
                TextColumn::make('total_gastos')
                ->money('USD')
                ->label('Gastos($)')
                ->sortable()
                ->searchable(),
                TextColumn::make('saldo_caja_chica')
                ->money('USD')
                ->label('Efectivo($) Caja Chica')
                ->sortable()
                ->searchable(),
                TextColumn::make('created_at')
                ->label('Fecha de cierre')
                ->sortable()
                ->searchable(),
                TextColumn::make('responsable')
                ->sortable()
                ->searchable(),
            ])
            ->groups([
                'responsable',
                'fecha'
            ])
            ->filters([
                // DateRangeFilterIterator::make('created_at')->timezone('America/Caracas'),
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.cierre-general');
    }
}
