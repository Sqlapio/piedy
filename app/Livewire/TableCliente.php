<?php

namespace App\Livewire;

use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\ClienteController;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Servicio;
use Filament\Enums\ThemeMode;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Support\RawJs;

class TableCliente extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('CLIENTES')
            ->description('Tabla de gestion de clientes')
            ->query(Cliente::query()->orderBy('created_at', 'desc'))
            ->columns([
                    TextInputColumn::make('nombre')
                        ->grow(false)
                        ->searchable(),
                    TextInputColumn::make('cedula')
                        ->rules(['numeric'])
                        ->searchable(),
                    TextInputColumn::make('email')
                        ->rules(['email'])
                        ->searchable(),
                    TextInputColumn::make('telefono')
                        ->rules(['numeric'])
                        ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('Asignar Servicio')
                    ->icon('heroicon-s-wallet')
                    ->color('colorOne')
                    ->form([
                        Select::make('user_id')
                            ->label('Selección del Técnico')
                            ->options(User::whereBetween('tipo_servicio_id', [1, 2])->where('status', 1)->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Select::make('servicio_id')
                            ->label('Selección del Servicio')
                            ->options(Servicio::orderBy('descripcion', 'asc')->pluck('descripcion', 'id'))
                            ->required()
                            ->searchable(),
                    ])->action(function (Cliente $record, array $data) {

                        //Controller para asignacion de servicio
                        $res = AsignacionController::asigancion_servicio($record->id, $data['user_id'], $data['servicio_id']);

                        if ($res) {
                            Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-o-shield-check')
                            ->iconColor('success')
                            ->body('El servicio fue asignado correctamente!')
                            ->send();

                        }else{
                            Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-s-exclamation-triangle')
                            ->iconColor('danger')
                            ->body('El tecnico ya posee un servicio abierto. Por favor realiza la facturación y vuelve a intentar!')
                            ->send();

                        }

                    }),
                ])
                ->icon('heroicon-c-adjustments-horizontal')
                ->color('colorOne')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Cliente::class)
                        ->form([
                            Section::make('Formulario')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-c-users')
                                ->schema([
                                    Grid::make()
                                    ->schema([

                                        //Nombre y apellido
                                        TextInput::make('nombre')
                                            ->label('Nombre y Apellido')
                                            ->prefixIcon('heroicon-c-users')
                                            ->required(),

                                        //Cedula
                                        TextInput::make('cedula')
                                            ->label('Cédula de Identidad')
                                            ->prefixIcon('heroicon-c-credit-card')
                                            // ->helps('Ejemplo: 16007868')
                                            ->numeric()
                                            ->required()
                                            ->mask('99999999'),

                                        //Email
                                        TextInput::make('email')
                                            ->label('Correo Electrónico')
                                            ->prefixIcon('heroicon-c-at-symbol')
                                            ->email()
                                            ->required(),

                                        //Telefono
                                        TextInput::make('telefono')
                                            ->label('Teléfono')
                                            ->prefixIcon('heroicon-c-device-phone-mobile')
                                            ->required()
                                            ->mask(RawJs::make(<<<'JS'
                                                $input.startsWith('1') ? '19999999999' : '9999-9999999'
                                            JS)),

                                    ]),
                                ])
                        ])
                            ->action(function (array $data) {
                                ClienteController::crear(
                                    $data['nombre'],
                                    $data['cedula'],
                                    $data['email'],
                                    $data['telefono']
                                );
                            })
            ])
            ->striped()
            ->defaultPaginationPageOption(8);
    }

    public function render(): View
    {
        return view('livewire.table-cliente');
    }
}
