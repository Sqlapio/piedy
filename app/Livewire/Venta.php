<?php

namespace App\Livewire;

use App\Models\VentaServicio;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class Venta extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(VentaServicio::query())
            ->columns([
                TextColumn::make('cod_asignacion')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('responsable')->searchable(),
                TextColumn::make('created_at')
                    ->label(__('Fecha de registro'))
                    ->searchable(),
                TextColumn::make('cliente')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('empleado')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('metodo_pago')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Facturación multiple'  => 'warning',
                        'Multiple'              => 'warning',
                        'Efectivo Usd'          => 'success',
                        'Zelle'                 => 'success',
                        'Efectivo Bsd'          => 'info',
                        'Pago movil'            => 'info',
                        'transferencia'         => 'info',
                        'Punto de venta'        => 'info',
                        'Anulado'               => 'danger',
                        'cliente especial'  => 'success',
                    }),
                TextColumn::make('referencia')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
                TextColumn::make('total_USD')
                    ->label(_('Costo($)'))
                    ->summarize(Sum::make()
                    ->numeric(
                        decimalPlaces: 00,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    ))
                    ->searchable(),

                TextColumn::make('pago_usd')->money('USD')
                    ->label(_('Pagos($)'))
                    ->summarize(Sum::make()
                        ->label(_('Pago Neto ($)'))
                        ->money('USD'))
                    ->searchable(),

                TextColumn::make('pago_bsd')->money('VES')
                    ->label(_('Pagos(Bs.)'))
                    ->summarize(Sum::make()
                        ->label(_('Pago Neto (Bs.)'))
                        ->money('VES'))
                    ->searchable(),

                TextColumn::make('comision_dolares')->money('USD')
                    ->label(_('Comisión ($)'))
                    ->summarize(Sum::make()
                        ->label(_('Comisión Neta ($)'))
                        ->money('USD'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('comision_bolivares')->money('VES')
                    ->label(_('Comisión (Bs.)'))
                    ->summarize(Sum::make()
                        ->label(_('Comisión Neta (Bs.)'))
                        ->money('VES'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('propina_usd')->money('USD')
                    ->summarize(Sum::make()
                    ->money('USD')
                    ->label('Total($)'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('propina_bsd')
                    ->summarize(Sum::make()
                    ->numeric(
                        decimalPlaces: 00,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->label('Total(Bs)'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->groups([
                'metodo_pago',
                'cliente',
                'empleado',
                'fecha_venta'
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
        return view('livewire.venta');
    }
}
