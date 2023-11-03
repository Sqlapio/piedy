<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisponibleResource\Pages;
use App\Filament\Resources\DisponibleResource\RelationManagers;
use App\Models\Disponible;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DisponibleResource extends Resource
{
    protected static ?string $model = Disponible::class;

    protected static ?string $navigationIcon = 'heroicon-m-swatch';

    protected static ?string $navigationGroup = 'Tienda Sambil';

    protected static ?string $navigationLabel = 'Puestos activos';

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
                TextColumn::make('cod_asignacion')->searchable(),
                TextColumn::make('cliente')->searchable(),
                TextColumn::make('empleado')->searchable(),
                TextColumn::make('servicio')->searchable(),
                TextColumn::make('servicio_categoria')->searchable(),
                TextColumn::make('area_trabajo')->searchable(),
                TextColumn::make('costo')->searchable(),
                IconColumn::make('status')
                ->options([
                    'heroicon-o-users' => fn ($state, $record): bool => $record->status === 'activo',
                ])
                ->colors([
                    'success' => 'activo',
                ])
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
            'index' => Pages\ListDisponibles::route('/'),
            'create' => Pages\CreateDisponible::route('/create'),
            'edit' => Pages\EditDisponible::route('/{record}/edit'),
        ];
    }    
}
