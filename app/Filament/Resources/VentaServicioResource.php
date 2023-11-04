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
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('empleado')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fecha_venta')->searchable(),
                TextColumn::make('metodo_pago')->searchable(),
                TextColumn::make('referencia')->searchable(),

                TextColumn::make('total_USD')
                ->summarize(Sum::make()
                ->money('USD')
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: ',',
                    thousandsSeparator: '.',
                )
                ->label('Venta Neta($)'))
                ->searchable(),
                // TextColumn::make('total_USD')->summarize(Sum::make()),

                TextColumn::make('pago_usd')->money('USD')
                ->summarize(Sum::make()
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: ',',
                    thousandsSeparator: '.',
                )
                ->label('Total($)'))
                ->searchable(),

                TextColumn::make('pago_bsd')
                ->summarize(Sum::make()
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: ',',
                    thousandsSeparator: '.',
                )
                ->label('Total(Bs)'))
                ->searchable(),

                TextColumn::make('comision_empleado')->money('USD')
                ->summarize(Sum::make()
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: ',',
                    thousandsSeparator: '.',
                )
                ->label('Neto Empleado'))
                ->searchable(),

                TextColumn::make('comision_gerente')->money('USD')
                ->summarize(Sum::make()
                ->numeric(
                    decimalPlaces: 00,
                    decimalSeparator: ',',
                    thousandsSeparator: '.',
                )
                ->label('Neto Gerente'))
                ->searchable(),
                
            ])
            ->groups([
                'metodo_pago',
                'empleado',
                'cod_asignacion',
                'fecha_venta'
            ])
            // ->groupRecordsTriggerAction(
            //     fn (Action $action) => $action
            //         ->button()
            //         ->label('Group records'),
            // )
            //->defaultGroup('empleado')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
