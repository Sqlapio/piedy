<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CierreDiarioResource\Pages;
use App\Filament\Resources\CierreDiarioResource\RelationManagers;
use App\Models\CierreDiario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CierreDiarioResource extends Resource
{
    protected static ?string $model = CierreDiario::class;

    protected static ?string $navigationIcon = 'heroicon-s-chart-pie';

    protected static ?string $navigationLabel = 'Cierre diario';

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
                TextColumn::make('total_pago_usd')->searchable()->label('Total pago($)'),
                TextColumn::make('total_pago_bsd')->searchable()->label('Total pago(Bs)'),
                TextColumn::make('venta_neta_usd')->searchable()->label('Venta Neta($)'),
                TextColumn::make('venta_neta_bsd')->searchable()->label('Venta Neta(Bs)'),
                TextColumn::make('created_at')->searchable()->label('Fecha de cierre'),
                TextColumn::make('responsable')->searchable()->label('Responsable'),
            ])
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
            'index' => Pages\ListCierreDiarios::route('/'),
            'create' => Pages\CreateCierreDiario::route('/create'),
            'edit' => Pages\EditCierreDiario::route('/{record}/edit'),
        ];
    }
}
