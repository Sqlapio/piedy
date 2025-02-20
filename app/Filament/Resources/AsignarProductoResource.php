<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AsignarProducto;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AsignarProductoResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\AsignarProductoResource\RelationManagers;

class AsignarProductoResource extends Resource
{
    protected static ?string $model = AsignarProducto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Modulo de Inventario';

    protected static ?string $navigationLabel = 'Productos Asignados';

    protected static ?int $navigationSort = 11;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('asignacion')
                ->label('Asignacion')
                ->badge()
                ->icon(fn(string $state): string => match ($state) {
                    'tienda' => 'heroicon-s-building-storefront',
                    'tecnico' => 'heroicon-c-user-plus',
                })
                ->color(fn(string $state): string => match ($state) {
                    'tienda' => 'warning',
                    'tecnico' => 'success',
                })
                ->searchable(isIndividual: true)
                ->alignCenter(),

                Tables\Columns\TextColumn::make('producto.descripcion')
                ->label('Producto')
                ->searchable(isIndividual: true),
                
                Tables\Columns\TextColumn::make('user.name')
                ->label('Usuario')
                ->searchable(isIndividual: true),
                
                Tables\Columns\TextColumn::make('cantidad')
                ->label('Cantidad')
                ->alignCenter()
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Fecha de Asignacion')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('servicios_facturados')
                ->label('Servicios o Dias ')
                ->alignCenter()
                ->numeric()
                ->sortable(),
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
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAsignarProductos::route('/'),
            'create' => Pages\CreateAsignarProducto::route('/create'),
            'edit' => Pages\EditAsignarProducto::route('/{record}/edit'),
        ];
    }
}