<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadoResource\Pages;
use App\Filament\Resources\EmpleadoResource\RelationManagers;
use App\Models\Empleado;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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

class EmpleadoResource extends Resource
{
    protected static ?string $model = Empleado::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('apellido')
                    ->required(),
                TextInput::make('cedula')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('telefono')
                    ->mask('(9999)999-99-99')
                    ->required(),
                TextInput::make('direccion_corta'),
                Select::make('tipo_empleado')
                    ->options([
                        'gerente' => 'Gerente',
                        'empleado' => 'Empleado',
                    ]),
                Select::make('area_trabajo')
                    ->options([
                        'Cubiculo 1' => 'c1',
                        'Cubiculo 2' => 'c2',
                        'Cubiculo 3' => 'c3',
                        'Cubiculo 4' => 'c4',
                        'Mesa 1' => 'm1',
                        'Mesa 2' => 'm2',
                        'Mesa 3' => 'm3',
                        'Mesa 4' => 'm4',
                        'No aplica' => 'na',
                    ]),
                DatePicker::make('fecha_ingreso')
                    ->format('d/m/Y')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre'),
                TextColumn::make('apellido'),
                TextColumn::make('cedula'),
                TextColumn::make('email'),
                TextColumn::make('telefono'),
                TextColumn::make('direccion_corta'),
                TextColumn::make('tipo_empleado'),
                TextColumn::make('area_trabajo'),
                TextColumn::make('fecha_ingreso'),
                IconColumn::make('status')
                ->options([
                    'heroicon-o-check-circle' => fn ($state, $record): bool => $record->status === 'activo',
                    'heroicon-o-clock' => fn ($state, $record): bool => $record->status === 'inactivo',
                ])
                ->colors([
                    'danger' => 'inactivo',
                    'success' => 'activo',
                ]),
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
            'index' => Pages\ListEmpleados::route('/'),
            'create' => Pages\CreateEmpleado::route('/create'),
            'edit' => Pages\EditEmpleado::route('/{record}/edit'),
        ];
    }    
}
