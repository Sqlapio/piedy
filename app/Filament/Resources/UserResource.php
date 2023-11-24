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
                TextInput::make('name')->required(),
                TextInput::make('email')
                ->email()
                ->required(),
                Select::make('tipo_usuario')
                    ->options([
                        'administrador' => 'Administrador',
                        'gerente' => 'Gerente',
                        'empleado' => 'Empleado',
                    ]),
                Select::make('area_trabajo')
                    ->options([
                        'm1' => 'Mesa-Manicure 1',
                        'm2' => 'Mesa-Manicure 2',
                        'm3' => 'Mesa-Manicure 3',
                        'm4' => 'Mesa-Manicure 4',
                        'c1' => 'Cubiculo-Podologia 1',
                        'c2' => 'Cubiculo-Podologia 2',
                        'c3' => 'Cubiculo-Podologia 3',
                        'c4' => 'Cubiculo-Podologia 4',
                        'Administración' => 'Administración',
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
                    'gerente' => 'info',
                    'empleado' => 'warning',
                })
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
