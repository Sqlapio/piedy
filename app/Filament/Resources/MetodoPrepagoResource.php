<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetodoPrepagoResource\Pages;
use App\Filament\Resources\MetodoPrepagoResource\RelationManagers;
use App\Models\MetodoPrepago;
use App\Models\Sucursal;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MetodoPrepagoResource extends Resource
{
    protected static ?string $model = MetodoPrepago::class;

    protected static ?string $navigationIcon = 'heroicon-m-gif';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Métodos Prepagados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descripcion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('moneda')
                    ->required()
                    ->maxLength(255),
                Select::make('sucursal_id')
                    ->label('Sucurcal')
                    ->options(Sucursal::all()->pluck('nombre', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('moneda')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->searchable(),
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
            'index' => Pages\ListMetodoPrepagos::route('/'),
            'create' => Pages\CreateMetodoPrepago::route('/create'),
            'edit' => Pages\EditMetodoPrepago::route('/{record}/edit'),
        ];
    }
}
