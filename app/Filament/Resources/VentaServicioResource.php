<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaServicioResource\Pages;
use App\Filament\Resources\VentaServicioResource\RelationManagers;
use App\Models\VentaServicio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
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
                TextColumn::make('metodo_pago')->searchable(),
                TextColumn::make('referencia')->searchable(),

                TextColumn::make('total_USD')
                    ->summarize(Sum::make()
                    ->money('USD')
                    ->label('Venta Neta($)'))
                    ->searchable(),
                // TextColumn::make('total_USD')->summarize(Sum::make()),

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
                DateRangeFilter::make('created_at')
                ->timezone('America/Caracas'),
            ])
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
            'index' => Pages\ListVentaServicios::route('/'),
            'create' => Pages\CreateVentaServicio::route('/create'),
            'edit' => Pages\EditVentaServicio::route('/{record}/edit'),
        ];
    }
}
