<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-circle';

    protected static ?string $navigationGroup = 'Sistema';

    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                TextInput::make('cedula')
                    ->required(),

                TextInput::make('email')
                    ->email()
                    ->required(),

                TextInput::make('telefono')
                    ->required(),

                Select::make('tipo_usuario')
                    ->options([
                        'administrador' => 'Administrador',
                        'gerente' => 'Gerente',
                        'empleado' => 'Empleado',
                        'encargado' => 'Encargado',
                        'nomina' => 'Nomina',
                    ]),

                Select::make('area_trabajo')
                    ->options([
                        'quiropedia' => 'Quiropedia',
                        'manicure' => 'Manicure',
                        'Tienda' => 'Tienda',
                        'Administración' => 'Administración',
                        'Nomina' => 'Nomina',
                    ])->searchable(),
                    
                Select::make('tipo_servicio_id')
                    ->relationship('tipo_servicio', 'descripcion')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('descripcion')
                            ->required(),
                    ])
                    ->required(),
                    
                TextInput::make('salario')
                    ->label('Salario Mensual')
                    ->prefix('$')
                    ->numeric()
                    ->inputMode('decimal'),
                    
                Select::make('status')
                    ->options([
                        '1' => 'Activo',
                        '2' => 'Inactivo',
                    ])->searchable(),
                    
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->hiddenOn('edit')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->label('Nombre y Apellido'),
                TextColumn::make('email')->searchable()->label('Correo electrónico'),
                TextColumn::make('tipo_usuario')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'administrador' => 'success',
                    'gerente' => 'success',
                    'encargado' => 'info',
                    'empleado' => 'warning',
                    'nomina' => 'gray',
                })
                ->searchable(),
                TextColumn::make('tipo_servicio.descripcion')->label('Tipo de servício')->searchable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
