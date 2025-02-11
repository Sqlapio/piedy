<?php

namespace App\Filament\Pages;

use Filament\Forms\Get;
use App\Models\Sucursal;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;

use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends \Filament\Pages\Dashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;


    protected static ?string $title = 'Dashboard Piedy';

    public function getTitle(): string
    {
        $user = Auth::user();

        return 'Hola, ' . ($user ? $user->name : 'Invitado') . '.';
    }

    protected static ?string $navigationIcon = 'heroicon-c-presentation-chart-bar';

    // protected static string $view = 'filament.pages.dashboard-new';

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                // Section::make()
                //     ->schema([
                //         Toggle::make('activar')
                //         ->label('Rango de Fechas')
                //         ->onColor('success')
                //         ->onIcon('heroicon-c-check')
                //         ->offColor('danger')
                //         ->offIcon('heroicon-c-x-mark')
                //         ->live()
                //         ->declined(),
                //     ])
                //     ->columns(2),
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')->label('Inicio'),
                        DatePicker::make('endDate')->label('Fin'),
                        Select::make('sucursal_id')
                        ->label('Sucursal')
                        ->options(Sucursal::all()->pluck('nombre', 'id'))
                        ])
                        ->columns(3),
                        // ->visible(fn(Get $get):bool => $get('activar')),
            ]);
    }
}