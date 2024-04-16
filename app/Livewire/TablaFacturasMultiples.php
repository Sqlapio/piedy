<?php

namespace App\Livewire;

use App\Models\FacturaMultiple;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Livewire\Component;


class TablaFacturasMultiples extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {

        $fecha_venta = date('d-m-Y');

        return $table
            ->query(FacturaMultiple::query()->where('fecha_venta', $fecha_venta))
            ->columns([
                TextColumn::make('responsable')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cod_asignacion')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cliente')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('empleado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Fecha de registro')
                    ->searchable(),
                TextColumn::make('metodo_pago')->searchable(),
                TextColumn::make('referencia')->searchable(),

                TextColumn::make('total_usd')->money('USD')
                    ->label('Costo servicio($)')
                    ->summarize(Sum::make()
                    ->label('Venta Neta($)'))
                    ->searchable(),

                TextColumn::make('pago_usd')->money('USD')
                ->label('Pago($)')
                ->summarize(Sum::make()
                ->label('Total($)'))
                ->searchable(),

                TextColumn::make('pago_bsd')->money('VES')
                ->label('Pago(Bs)')
                ->summarize(Sum::make()
                ->label('Total(Bs)'))
                ->searchable(),

            ])
            ->groups([
                'metodo_pago',
                'cliente',
                'empleado',
                'fecha_venta',
                'responsable'
            ])
            // ->defaultGroup('empleado')
            ->filters([
                Filter::make('created_at')
                ->form([
                    DatePicker::make('desde'),
                    DatePicker::make('hasta'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['desde'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['hasta'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['desde'] ?? null) {
                        $indicators['desde'] = 'Venta desde ' . Carbon::parse($data['desde'])->toFormattedDateString();
                    }
                    if ($data['hasta'] ?? null) {
                        $indicators['hasta'] = 'Venta hasta ' . Carbon::parse($data['hasta'])->toFormattedDateString();
                    }

                    return $indicators;
                }),
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.tabla-facturas-multiples');
    }
}
