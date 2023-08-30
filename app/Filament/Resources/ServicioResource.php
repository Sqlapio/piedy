<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServicioResource\Pages;
use App\Filament\Resources\ServicioResource\RelationManagers;
use App\Models\Servicio;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\TablesServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServicioResource extends Resource
{
    protected static ?string $model = Servicio::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('cod_servicio')->default('Pco-'.random_int(11111, 99999)),
                TextInput::make('descripcion')->required(),
                TextInput::make('costo')
                    ->prefix('$')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->required(), 
                TextInput::make('duracion_max')
                    ->prefix('Minutos')
                    ->numeric()
                    ->required(),
                Select::make('comision_id')
                    ->relationship('comision', 'porcentaje')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('cod_comision')->default('Pco-'.random_int(11111, 99999)),
                        TextInput::make('porcentaje')->required(),
                    ])
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_servicio'),
                TextColumn::make('descripcion'),
                TextColumn::make('costo')->money('USD'),
                TextColumn::make('comision.porcentaje'),
                TextColumn::make('duracion_max'),
                IconColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'inactivo' => 'warning',
                })
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
            'index' => Pages\ListServicios::route('/'),
            'create' => Pages\CreateServicio::route('/create'),
            'edit' => Pages\EditServicio::route('/{record}/edit'),
        ];
    }    
}
