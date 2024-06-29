<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends \Filament\Pages\Dashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Toggle::make('activar')
                        ->label('Rango de Fechas')
                        ->onColor('success')
                        ->onIcon('heroicon-c-check')
                        ->offColor('danger')
                        ->offIcon('heroicon-c-x-mark')
                        ->live()
                        ->declined(),
                    ])
                    ->columns(2),
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')->label('Inicio'),
                        DatePicker::make('endDate')->label('Fin'),
                        ])
                        ->columns(2)
                        ->visible(fn(Get $get):bool => $get('activar')),
            ]);
    }
}
