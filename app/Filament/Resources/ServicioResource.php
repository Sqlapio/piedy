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
                Select::make('asignacion')
                    ->options([
                        'general' => 'General',
                        'promocion' => 'Promoción',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_servicio')->searchable(),
                TextColumn::make('descripcion')->searchable(),
                TextColumn::make('categoria')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'principal' => 'success',
                    'adicional' => 'warning',
                })
                ->searchable(),
                TextColumn::make('costo')->money('USD')->searchable(),
                TextColumn::make('duracion_max')->searchable(),
                IconColumn::make('status')
                ->options([
                    'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === 'activo',
                    'heroicon-m-minus-circle' => fn ($state, $record): bool => $record->status === 'inactivo',
                ])
                ->colors([
                    'danger' => 'inactivo',
                    'success' => 'activo',
                ]),
                TextColumn::make('asignacion')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'general' => 'warning',
                    'promocion' => 'success',
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
