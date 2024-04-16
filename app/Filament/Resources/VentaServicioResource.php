<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaServicioResource\Pages;
use App\Filament\Resources\VentaServicioResource\RelationManagers;
use App\Filament\Resources\VentaServicioResource\Widgets\StatsVenta;
use App\Filament\Resources\VentaServicioResource\Widgets\VentaServicioComisionStats;
use App\Filament\Resources\VentaServicioResource\Widgets\VentaServicioStats;
use App\Models\VentaServicio;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;


class VentaServicioResource extends Resource
{
    protected static ?string $model = VentaServicio::class;

    protected static ?string $navigationIcon = 'heroicon-m-chart-bar-square';

    protected static ?string $navigationLabel = 'Dashboard Ventas';

    protected static ?string $navigationGroup = 'Ventas';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_asignacion')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('empleado')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')->searchable()->label('Fecha de venta'),
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
                    ->label(('Costo servicio($)'))
                        ->summarize(Sum::make()
                        ->label(('Total'))
                        ->money('USD'))
                    ->searchable(),

                TextColumn::make('pago_usd')->money('USD')
                    ->label(('Pagos($)'))
                    ->summarize(Sum::make()
                        ->label(('Total'))
                        ->money('USD'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('pago_bsd')->money('VES')
                    ->label(('Pagos(Bs.)'))
                    ->summarize(Sum::make()
                        ->label(('Total'))
                        ->money('VES'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comision_dolares')->money('USD')
                    ->label(('Comision($)'))
                        ->summarize(Sum::make()
                            ->money('USD')
                            ->label('Neto Empleado($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comision_bolivares')->money('VES')
                    ->label(('Comision(Bs.)'))
                        ->summarize(Sum::make()
                            ->money('VES')
                            ->label('Neto Empleado(Bs.)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                'empleado',
                'cod_asignacion',
                'fecha_venta',
                'referencia',
                'cliente'
            ])
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
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            VentaServicioStats::class,
            VentaServicioComisionStats::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentaServicios::route('/'),
            'create' => Pages\CreateVentaServicio::route('/create'),
            'edit' => Pages\EditVentaServicio::route('/{record}/edit'),
        ];
    }

}
