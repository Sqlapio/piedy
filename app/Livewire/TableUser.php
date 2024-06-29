<?php

namespace App\Livewire;

use App\Filament\Resources\UserResource;
use App\Livewire\FormularioCrearUsuario;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;


class TableUser extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $user;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->where('status', '1')->whereBetween('tipo_usuario', ['empleado', 'encargado']))
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre y Apellido')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cedula')
                    ->label('Cedula')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('telefono')
                    ->label('Telefono')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('area_trabajo')
                    ->label('Area de Trabajo')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups(['area_trabajo'])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Crear Usuario')
                    ->model(User::class)
                    ->form([
                        TextInput::make('name')->required(),
                        TextInput::make('cedula')->required(),
                        TextInput::make('email')
                            ->email()
                            ->required(),
                        TextInput::make('telefono')->required(),
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
                    ])
                    // ->slideOver()
            ])
            ->actions([
                Action::make('Eliminar')
                    ->icon('heroicon-o-trash')
                    ->action(function (User $record) {
                        $record->status = '2';
                        $record->save();
                    })
                    ->color('danger')
                    ->requiresConfirmation()
                    ->tooltip('Eliminar empleado'),
                // ...
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-user');
    }
}
