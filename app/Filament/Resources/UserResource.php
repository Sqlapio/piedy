<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Servicio;
use App\Models\Sucursal;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-circle';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Usuarios';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'success' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('REGISTRO DE USUARIOS')
            ->description('Formulario')
            ->icon('heroicon-c-user-circle')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre y Apellido')
                            ->required(),

                TextInput::make('cedula')
                            ->label('Cédula de Identidad')
                            ->hiddenOn('edit')
                            ->required()
                            ->rules(['required','numeric','unique:users,cedula'])
                            ->validationMessages([
                                'required'  => 'Campo requerido',
                                'numeric'    => 'Solo admite números',
                                'unique'    => 'El número de cédula esta duplicado',
                            ]),

                TextInput::make('email')
                            ->email()
                            ->required()
                            ->rules(['required','email','unique:users,email'])
                            ->hiddenOn('edit')
                            ->validationMessages([
                                'required'  => 'Campo requerido',
                                'unique'    => 'El email esta duplicado',
                            ]),

                TextInput::make('telefono')
                            ->label('Teléfono')
                            ->required(),

                        Select::make('rol_id')
                            ->relationship('rol', 'descripcion')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('descripcion')
                                    ->required(),
                            ])
                            ->required()
                            ->live(),

                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->hiddenOn('edit')
                            ->required(),
                        
                        Forms\Components\Select::make('sucursal_id')
                            ->prefixIcon('heroicon-s-home')
                            ->label('Sucursal')
                            ->options(Sucursal::all()->pluck('nombre', 'id'))
                            ->required(),

                        Select::make('servicios')
                        ->multiple()
                        ->relationship(name: 'servicios', titleAttribute: 'descripcion')
                        ->searchable()
                        ->preload()
                        ->hidden(function (Get $get) {
                            if($get('rol_id') == 1 || $get('rol_id') == 2)
                            {
                                return false;
                            }else{
                                return true;
                            }
                        }),
                    ])
                    ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextInputColumn::make('name')
                    // ->icon('heroicon-s-user-circle')
                    ->searchable()
                    ->label('Nombre y Apellido'),
                TextInputColumn::make('email')
                    // ->icon('heroicon-m-at-symbol')
                    ->searchable()
                    ->label('Correo electrónico'),
                TextInputColumn::make('cedula')
                    ->searchable()
                    ->label('Cedula'),
                TextInputColumn::make('telefono')
                    // ->icon('heroicon-o-device-phone-mobile')
                    ->searchable()
                    ->label('Teléfono'),
            TextColumn::make('rol.descripcion')
                    ->badge()
                    ->searchable()
                    ->label('Correo electrónico'),
                
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['hasta'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['desde'] ?? null) {
                            $indicators['desde'] = 'Venta desde ' . Carbon::parse($data['desde'])->toFormattedDateString();
                        }
                        if ($data['hasta'] ?? null) {
                            $indicators['hasta'] = 'Venta hasta ' . Carbon::parse($data['hasta'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
                SelectFilter::make('tienda')
                    ->relationship('sucursal', 'nombre')
                    ->attribute('sucursal_id')
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
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