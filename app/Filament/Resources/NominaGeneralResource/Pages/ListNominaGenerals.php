<?php

namespace App\Filament\Resources\NominaGeneralResource\Pages;

use App\Filament\Resources\NominaGeneralResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Rol;
use App\Models\TasaBcv;
use App\Models\Sucursal;
use App\Models\PreNomina;
use Filament\Actions\Action;
use App\Models\NominaGeneral;
use App\Models\DetalleEsGanPer;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use App\Http\Controllers\LogController;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\PreNominaResource;
use App\Http\Controllers\PreNominaController;

class ListNominaGenerals extends ListRecords
{
    protected static string $resource = NominaGeneralResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('Calcular Nomina')
            // ->label('Cálculo de Nomina')
            ->modal()
                ->color('colorOne')
                ->form([
                    Section::make('Formulario')
                    ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                    ->icon('heroicon-s-newspaper')
                    ->schema([
                        Grid::make()
                            ->schema([

                                //desde
                                DatePicker::make('fecha_ini')
                                ->label('Fecha Desde:')
                                ->prefixIcon('heroicon-m-calendar-days')
                                ->format('Y-m-d')
                                ->required(),

                                //hasta
                                DatePicker::make('fecha_fin')
                                ->label('Fecha Hasta:')
                                ->prefixIcon('heroicon-m-calendar-days')
                                ->format('Y-m-d')
                                ->required(),

                                //tipo de rol
                                Select::make('rol_id')
                                ->label('Tipo de Rol')
                                // ->relationship('rol', 'descripcion')
                                ->options(Rol::all()->pluck('descripcion', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->multiple()
                                    ->required(),

                                Select::make('sucursal_id')
                                ->label('Sucursal')
                                // ->relationship('sucursal', 'nombre')
                                ->options(Sucursal::all()->pluck('nombre', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                // ...
                            ]),
                    ])
                ])
                ->action(function (array $data) {

                    $cod_nomina = rand('111111', '999999');

                    //Creamos el asiento inicial para el pre calculo de la nomina
                    $asiento = new NominaGeneral();
                    $asiento->cod_nomina = $cod_nomina;
                    $asiento->total_bolivares = 0.00;
                    $asiento->total_dolares = 0.00;
                    $asiento->total_general = 0.00;
                    $asiento->tasa_bcv = TasaBcv::where("fecha", date('d-m-Y'))->first()->tasa;
                    $asiento->fecha_ini = $data['fecha_ini'];
                    $asiento->fecha_fin = $data['fecha_fin'];
                    $asiento->responsable = Auth::user()->name;
                    $asiento->sucursal_id = Auth::user()->sucursal_id;
                    $asiento->save();

                    try {

                        for ($i = 0; $i < count($data['rol_id']); $i++) {
                            # code...
                            $calculo = PreNominaController::calculo_pre_nomina(
                                $data['fecha_ini'],
                                $data['fecha_fin'],
                                $data['rol_id'][$i],
                                $data['sucursal_id'],
                                $cod_nomina,
                                $asiento->id
                            );

                            $rol = Rol::find($data['rol_id'][$i]);

                            if ($calculo == true) {
                                Notification::make()
                                    ->title('Notificacion')
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('success')
                                    ->color('success')
                                    ->body('La nomina de rol. ' . $rol->descripcion . ' se ha calculado con exito')
                                    ->send();
                            }
                        }
                    } catch (\Throwable $th) {
                        LogController::log(Auth::user()->id, 'excepcion-PreNominaResource::ListPreNominas', $th->getMessage(), $response = null);
                        Notification::make()
                            ->title('Notificacion: CajaController::multiple() ')
                            ->icon('heroicon-o-shield-check')
                            ->iconColor('danger')
                            ->body($th->getMessage())
                            ->send();
                    }
                })
        ];
    }
}