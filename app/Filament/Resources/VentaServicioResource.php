<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaServicioResource\Pages;
use App\Filament\Resources\VentaServicioResource\RelationManagers;
use App\Filament\Resources\VentaServicioResource\Widgets\StatsVenta;
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
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;


class VentaServicioResource extends Resource
{
    protected static ?string $model = VentaServicio::class;

    protected static ?string $navigationIcon = 'heroicon-m-chart-bar-square';

    protected static ?string $navigationGroup = 'Ventas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_asignacion')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cliente')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    }),
                TextColumn::make('referencia')->searchable(),

                TextColumn::make('total_USD')
                    ->summarize(Sum::make()
                    ->money('USD')
                    ->label('Venta Neta($)'))
                    ->searchable(),

                TextColumn::make('pago_usd')->money('USD')
                    ->summarize(Sum::make()
                    ->money('USD')
                    ->label('Total($)'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('pago_bsd')
                    ->summarize(Sum::make()
                    ->numeric(
                        decimalPlaces: 00,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->label('Total(Bs)'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comision_empleado')->money('USD')
                    ->summarize(Sum::make()
                    ->money('USD')
                    ->label('Neto Empleado($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comision_gerente')->money('USD')
                    ->summarize(Sum::make()
                    ->money('USD')
                    ->label('Neto Gerente($)'))
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
                'referencia'
            ])
            ->filters([
                Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['created_from'] ?? null) {
                        $indicators['created_from'] = 'Venta desde ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                    }
                    if ($data['created_until'] ?? null) {
                        $indicators['created_until'] = 'Venta hasta ' . Carbon::parse($data['created_until'])->toFormattedDateString();
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    }
}
