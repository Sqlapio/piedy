<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PonderacionResource\Pages;
use App\Filament\Resources\PonderacionResource\RelationManagers;
use App\Models\Ponderacion;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PonderacionResource extends Resource
{
    protected static ?string $model = Ponderacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-thumb-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('estrellas'),
                TextInput::make('costo'),
                FileUpload::make('image')->image()
            ]);
    }

    public static function table(Table $table): Table
    {
        
        return $table
            ->columns([
                TextColumn::make('estrellas'),
                TextColumn::make('costo')->money('USD'),
                ImageColumn::make('image')
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
            'index' => Pages\ListPonderacions::route('/'),
            'create' => Pages\CreatePonderacion::route('/create'),
            'edit' => Pages\EditPonderacion::route('/{record}/edit'),
        ];
    }    
}
