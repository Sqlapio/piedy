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

    protected static ?string $navigationIcon = 'heroicon-m-puzzle-piece';

    protected static ?string $navigationGroup = 'Administración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('cod_servicio')->default('Sco-'.random_int(11111, 99999)),
                TextInput::make('descripcion')->required(),
                Select::make('categoria')
                    ->options([
                        'principal' => 'Principal',
                        'adicional' => 'Adicional',
                    ]),
                Select::make('tipo_servicio_id')
                    ->relationship('tipo_servicio', 'descripcion')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('descripcion')
                            ->required(),
                    ])
                    ->required(),
                TextInput::make('costo')
                    ->prefix('$')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->required(),
                Select::make('promocion_id')
                    ->relationship('promocion', 'tipo')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_servicio')->searchable()->label('Código')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('descripcion')->searchable(),
                TextColumn::make('categoria')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'principal' => 'success',
                        'adicional' => 'warning',
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tipo_servicio.descripcion')->label('Tipo de servício')->searchable(),
                TextColumn::make('costo')->money('USD')->searchable()->label('Costo($)'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'inactivo' => 'error',
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('promocion.descripcion')->label('Promoción')->searchable()->default('Sin promoción'),
                TextColumn::make('promocion.tipo')->label('Tipo promoción')->searchable()->default('Sin promoción'),
                TextColumn::make('promocion.porcentaje')->label('Descuento(%)')->searchable()->default('0.00'),
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
