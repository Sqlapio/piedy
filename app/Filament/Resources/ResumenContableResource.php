<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\ResumenContable;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ResumenContableResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\ResumenContableResource\RelationManagers;

class ResumenContableResource extends Resource
{
    protected static ?string $model = ResumenContable::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
        ->query(ResumenContable::query()->orderBy('created_at', 'desc'))
            ->columns([

                Tables\Columns\TextColumn::make('codigo')
                ->label('Codigo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                ->label('Descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                ->label('Tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_usd')
                ->label('Monto USD')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('monto_bsd')
                ->label('Monto BSD')
                ->money('VES')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tasa_bcv')
                ->label('Tasa BCV')
                ->money('VES')
                    ->sortable(),
                Tables\Columns\TextColumn::make('conversion')
                ->label('Conversion')
                ->money('USD')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_operacion')
                ->label('Total Operacion')
                ->summarize(Sum::make()
                        ->money('USD')
                        ->label(('Total')))
                    ->sortable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                ->label('Sucursal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                ->label('Responsable')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('fecha')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListResumenContables::route('/'),
            'create' => Pages\CreateResumenContable::route('/create'),
            'edit' => Pages\EditResumenContable::route('/{record}/edit'),
        ];
    }
}