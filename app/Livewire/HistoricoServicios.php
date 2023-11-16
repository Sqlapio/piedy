<?php

namespace App\Livewire;

use App\Models\VentaServicio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class HistoricoServicios extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        $user = Auth::user();
        return $table
            ->query(VentaServicio::where('empleado_id', $user->id))
            ->columns([
                TextColumn::make('cod_asignacion')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('cliente')
                    ->searchable()
                    ->sortable(),
                    // ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fecha_venta')->searchable(),
                TextColumn::make('total_USD')
                    ->summarize(Sum::make()
                    ->numeric(
                        decimalPlaces: 00,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->label('Venta Neta($)'))
                    ->searchable(),
                TextColumn::make('comision_empleado')->money('USD')
                    ->summarize(Sum::make()
                    ->money('USD')
                    ->label('Neto Empleado($)'))
                    ->searchable()
                    ->sortable(),
                    // ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->groups([
                'metodo_pago',
                'cliente',
                'empleado',
                'fecha_venta'
            ])
            // ->defaultGroup('empleado')
            ->filters([
                DateRangeFilter::make('created_at')
                ->timezone('America/Caracas'),
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
        $user = Auth::user();

        return view('livewire.historico-servicios');
    }
}
