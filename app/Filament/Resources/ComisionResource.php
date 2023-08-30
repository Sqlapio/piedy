<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComisionResource\Pages;
use App\Filament\Resources\ComisionResource\RelationManagers;
use App\Models\Comision;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComisionResource extends Resource
{
    protected static ?string $model = Comision::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('cod_comision')->default('Pco-'.random_int(11111, 99999)),
                TextInput::make('porcentaje')
                    ->prefix('%')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->required(),
                Select::make('aplicacion')
                    ->options([
                        'producto' => 'Producto',
                        'servicio' => 'Servicio',
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_comision'),
                TextColumn::make('porcentaje'),
                TextColumn::make('aplicacion'),
                IconColumn::make('status')
                ->options([
                    'heroicon-o-check-circle' => fn ($state, $record): bool => $record->status === 'activo',
                    'heroicon-o-clock' => fn ($state, $record): bool => $record->status === 'inactivo',
                ])
                ->colors([
                    'danger' => 'inactivo',
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListComisions::route('/'),
            'create' => Pages\CreateComision::route('/create'),
            'edit' => Pages\EditComision::route('/{record}/edit'),
        ];
    }    
}
