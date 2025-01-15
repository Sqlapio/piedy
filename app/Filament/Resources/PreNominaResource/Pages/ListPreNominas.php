<?php

namespace App\Filament\Resources\PreNominaResource\Pages;

use App\Models\Rol;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PreNominaResource;
use App\Http\Controllers\PreNominaController;
use App\Models\Sucursal;

class ListPreNominas extends ListRecords
{
    protected static string $resource = PreNominaResource::class;

    protected ?string $heading = 'Módulo de Nomina';

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
                                        // ->relationship('rol', 'descripcion')
                                        ->options(Rol::all()->pluck('descripcion', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    Select::make('sucursal_id')
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
                    PreNominaController::calculo_pre_nomina(
                        $data['fecha_ini'],
                        $data['fecha_fin'],
                        $data['rol_id'],
                        $data['sucursal_id'],
                    );
                })
        ];
    }
}
