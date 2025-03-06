<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\DetalleAsignacion;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DetalleAsignacionResource\Pages;
use App\Filament\Resources\DetalleAsignacionResource\RelationManagers;

class DetalleAsignacionResource extends Resource
{
    protected static ?string $model = DetalleAsignacion::class;

    protected static ?string $navigationIcon = 'heroicon-s-squares-plus';

    protected static ?string $navigationLabel = 'Asigancion Servicios/Productos';

    protected static ?string $navigationGroup = 'Modulo Administrativo';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_asignacion')
                ->label('Codigo Asignacion')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('cod_prod_serv')
                //     ->label('Codigo Producto/Servicio')
                //     ->sortable()
                //     ->searchable(),
                Tables\Columns\TextColumn::make('empleado.name')
                    ->label('Empleado')
                    ->numeric()
                    ->sortable()    
                    ->searchable(),
                Tables\Columns\TextColumn::make('servicio.descripcion')
                    ->label('Servicio')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('costo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha Asignacion')
                    ->dateTime()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->numeric()
                    ->sortable(),
            ])
            ->groups([
                Group::make('servicio_id')
                    ->label('Servicio'),
                Group::make('cliente_id')
                    ->label('Cliente'),
                // 'servicio_id',
                // 'empleado_id',
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
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['hasta'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('servicio')
                    ->relationship('servicio', 'descripcion')
                    ->attribute('servicio_id')
                    ->multiple()
                    ->searchable()
                    ->preload()
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDetalleAsignacions::route('/'),
            'create' => Pages\CreateDetalleAsignacion::route('/create'),
            'edit' => Pages\EditDetalleAsignacion::route('/{record}/edit'),
        ];
    }
}