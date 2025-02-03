<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Sucursal;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VentaProducto;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VentaProductoResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\VentaProductoResource\RelationManagers;


class VentaProductoResource extends Resource
{
    protected static ?string $model = VentaProducto::class;

    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-bar';

    protected static ?string $navigationGroup = 'Modulo Administrativo';

    protected static ?string $navigationLabel = 'Productos';

    protected static ?int $navigationSort = 7;

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
            ->query(VentaProducto::query()->orderBy('created_at', 'desc'))
            ->columns([
                // TextColumn::make('cod_asignacion')
                // ->label('Códido')
                // ->searchable()
                //     ->sortable(),
                TextColumn::make('producto.descripcion')
                    ->icon('heroicon-s-shopping-bag')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('empleado.name')
                    ->label('Vendido por:')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Venta')
                    ->icon('heroicon-s-calendar-days')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('costo_producto')
                    ->label('Costo')
                    ->money('USD')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->icon('heroicon-o-square-3-stack-3d')
                    ->color('success')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->icon('heroicon-s-building-office-2')
                    ->color('colorTree')
                    ->numeric()
                    ->searchable(),

                TextColumn::make('total_venta')
                    ->label('Total de venta')
                    ->money('USD')
                        ->summarize(Sum::make()
                            ->money('USD')
                            ->label('Total($)')
                        )
                    ->sortable(),
                //Campos Ocultos
                TextColumn::make('metodo_pago')
                    ->label('Metodo de Pago')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('metodoUsd')
                    ->label('Metodo($)')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('metodoBsd')
                    ->label('Metodo(Bs.)')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comision_empleado')
                    ->label(('Comision Empleado($)'))
                    ->color('colorTwo')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Neto Empleado($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comision_gerente')
                    ->label(('Comision Gerente($)'))
                    ->color('colorTwo')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Neto Gerente($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('montoUsd')
                    ->label(('Monto($)'))
                    ->icon('heroicon-m-currency-dollar')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Neto($)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('montoBsd')
                    ->label(('Monto(Bs.)'))
                    ->icon('heroicon-s-credit-card')
                    ->money('VES')
                    ->summarize(Sum::make()
                        ->money('VES')
                        ->label('Neto(Bs.)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('fecha_venta')
                    ->label('Fecha Venta')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    SelectFilter::make('tienda')
                    ->relationship('sucursal', 'nombre')
                    ->attribute('sucursal_id')      
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
                    ExportBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentaProductos::route('/'),
            'create' => Pages\CreateVentaProducto::route('/create'),
            'edit' => Pages\EditVentaProducto::route('/{record}/edit'),
        ];
    }
}