<?php

namespace App\Livewire;

use App\Models\CierreDiario;
use App\Models\Gasto;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use WireUi\Traits\Actions;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\Action;
use Livewire\Component;

class TablaCierreGastos extends Component implements HasForms, HasTable
{
    use Actions;
    use InteractsWithTable;
    use InteractsWithForms;


    public function table(Table $table): Table
    {

        return $table
            ->query(Gasto::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
            ->columns([
                TextColumn::make('monto_usd')
                ->label('Gasto($)')
                ->money('USD')
                ->sortable()
                ->searchable(),
                TextColumn::make('monto_bsd')
                ->label('Gasto(Bs.)')
                ->money('VES')
                ->sortable()
                ->searchable(),
                TextColumn::make('created_at')
                ->label('Fecha de gasto')
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
                'observaciones',
            ])
            ->filters([
                DateRangeFilter::make('created_at')->timezone('America/Caracas'),
                // ...
            ])
            ->actions([

            ])
            ->bulkActions([
                // ...
            ]);

    }

    public function render()
    {
        return view('livewire.tabla-cierre-gastos');
    }
}
