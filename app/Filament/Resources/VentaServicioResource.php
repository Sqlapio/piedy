<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaServicioResource\Pages;
use App\Filament\Resources\VentaServicioResource\Widgets\VentaServicioComisionStats;
use App\Filament\Resources\VentaServicioResource\Widgets\VentaServicioStats;
use App\Models\VentaServicio;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;


class VentaServicioResource extends Resource
{
    protected static ?string $model = VentaServicio::class;

    protected static ?string $navigationIcon = 'heroicon-m-chart-bar-square';

    protected static ?string $navigationLabel = 'Servícios';

    protected static ?string $navigationGroup = 'Ventas';

    public static function table(Table $table): Table
    {
        return $table
            // ->query(VentaServicio::query()->where('status', '1'))
            ->columns([
                TextColumn::make('cod_asignacion')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('cliente_id')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('empleado_id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('created_at')->searchable()
                    ->label('Fecha de venta')
                    ->toggleable(isToggledHiddenByDefault: false),
                    
                TextColumn::make('metodo_pago')
                    ->label('Metodo Pago($)')
                    ->description(fn (VentaServicio $record): string => $record->metodo_pago_dos)
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('membresia_exp')
                    ->label('Membresia EXP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                TextColumn::make('total_USD')
                    ->label(('Costo de Servício($)'))
                        ->summarize(Sum::make()
                        ->label(('Total'))
                        ->money('USD'))
                        ->alignCenter()
                    ->searchable(),

                TextColumn::make('pago_usd')->money('USD')
                    ->label(('Pagos($)'))
                    ->summarize(Sum::make()
                        ->label(('Total'))
                        ->money('USD'))
                        ->alignCenter()
                    ->searchable(),

                TextColumn::make('pago_bsd')
                    ->toggleable(isToggledHiddenByDefault: false)
                        ->label(('Pagos(Bs.)'))
                        ->summarize(Sum::make()
                            ->label(('Total')))
                            ->alignCenter()
                        ->searchable(),

                TextColumn::make('comision_gerente')->money('USD')
                    ->label(('Comision Gte.($)'))
                        ->summarize(Sum::make()
                            ->money('USD')
                            ->label('Neto Gerente($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comision_dolares')->money('USD')
                    ->label(('Comision($)'))
                        ->summarize(Sum::make()
                            ->money('USD')
                            ->label('Neto Empleado($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comision_bolivares')
                    ->label(('Comision(Bs.)'))
                        ->summarize(Sum::make()
                            ->label('Neto Empleado(Bs.)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comision_emp_venprod')
                    ->label(('Comision Producto($)'))
                        ->summarize(Sum::make()
                            ->label('Neto Comision Producto(Bs.)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('propina_usd')
                    ->money('USD')
                    ->label('Propina($)')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Total($)'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('propina_bsd')
                    ->label('Propina(Bs.)')
                    ->summarize(Sum::make()
                        ->label('Total(Bs.)'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('referencia_propina')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('duracion')
                ->label('Duración(Minutos)')
                    ->sortable()
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
            ])
            ->striped();
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