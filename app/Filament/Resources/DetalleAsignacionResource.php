<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetalleAsignacionResource\Pages;
use App\Filament\Resources\DetalleAsignacionResource\RelationManagers;
use App\Models\DetalleAsignacion;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetalleAsignacionResource extends Resource
{
    protected static ?string $model = DetalleAsignacion::class;

    protected static ?string $navigationIcon = 'heroicon-m-chart-bar-square';

    protected static ?string $navigationLabel = 'Dashboard Empleados';

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
                TextColumn::make('servicio')
                    ->sortable()
                    ->label(('Servícios'))
                        ->summarize(Count::make()
                        ->label(('Total de Servicios')))
                    ->searchable(),
                TextColumn::make('empleado')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')->searchable()->label('Fecha de venta'),

                TextColumn::make('costo')
                    ->label(('Costo servicio($)'))
                        ->summarize(Sum::make()
                        ->label(('Total'))
                        ->money('USD'))
                    ->searchable(),

                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1'  => 'warning',
                        '2'  => 'success',
                        '3'  => 'success',
                    }),
                TextColumn::make('duracion')
                    ->label(('Tiempo(min)'))
                    ->searchable()
                    ->sortable()
            ])
            ->groups([
                'empleado',
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDetalleAsignacions::route('/'),
            // 'create' => Pages\CreateDetalleAsignacion::route('/create'),
            // 'edit' => Pages\EditDetalleAsignacion::route('/{record}/edit'),
        ];
    }
}
