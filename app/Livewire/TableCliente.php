<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Tables;
use App\Models\Cliente;
use Filament\Forms\Get;
use Livewire\Component;
use App\Models\Servicio;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\ServicioUser;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use App\Http\Controllers\LogController;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use App\Http\Controllers\ClienteController;
use Filament\Tables\Columns\TextInputColumn;
use App\Http\Controllers\AsignacionController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableCliente extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('CLIENTES')
            ->description('Tabla de gestión de clientes')
            ->query(Cliente::query()->orderBy('created_at', 'desc'))
            ->columns([
                    TextInputColumn::make('nombre')
                        ->label('Nombre')
                        ->grow(false)
                        ->searchable(),
                    TextInputColumn::make('cedula')
                        ->label('Cédula')
                        ->rules(['numeric'])
                        ->searchable(),
                    TextInputColumn::make('email')
                        ->rules(['email'])
                        ->searchable(),
                    TextInputColumn::make('telefono')
                        ->label('Teléfono')
                        ->rules(['numeric'])
                        ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Asignar Servicio')
                ->icon('heroicon-s-wallet')
                ->color('colorOne')
                ->form([
                    Select::make('user_id')
                        ->label('Selección del Técnico')
                        ->options(User::whereBetween('rol_id', [1,2])->where('status', 1)->pluck('name', 'id'))
                        ->required()
                        ->live()
                        ->searchable(),
                    Select::make('servicio_id')
                        ->label('Seleccione el Servício')
                        ->options(fn (Get $get): Collection => ServicioUser::query()
                        ->where('user_id', $get('user_id'))
                        ->pluck('descripcion', 'servicio_id'))
                        // ->options(Servicio::orderBy('descripcion', 'asc')->pluck('descripcion', 'id'))
                        ->required()
                        ->searchable(),
                ])->action(function (Cliente $record, array $data) {
                    //Controller para asignacion de servicio
                    $res = AsignacionController::asignacion_servicio($record->id, $data['user_id'], $data['servicio_id']);

                    if ($res) {
                        LogController::log(Auth::user()->id, 'asigno servicio', 'Asigno el servicio: '.Servicio::where('id', $data['servicio_id'])->first()->descripcion, $response = null);
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
                        ->body('El técnico ya posee un servicio abierto. Por favor realiza la facturación y vuelve a intentar!')
                        ->send();

                    }

                })
                ->icon('heroicon-s-swatch')
                ->color('success')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->headerActions([
                CreateAction::make()
                ->color('success')
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
                                            ->rules(['required', 'regex:/^[A-Za-z0-9\s]+$/u'])
                                            ->validationMessages([
                                                'required'  => 'Campo requerido',
                                                'regex'    => 'Solo admite letras',
                                            ]),

                                        //Cedula
                                        TextInput::make('cedula')
                                            ->label('Cédula de Identidad')
                                            ->prefixIcon('heroicon-c-credit-card')
                                            // ->helps('Ejemplo: 16007868')
                                            ->numeric()
                                            ->mask('99999999')
                                            ->rules(['required','numeric','unique:clientes,cedula'])
                                            ->validationMessages([
                                                'required'  => 'Campo requerido',
                                                'numeric'   => 'Solo admite números',
                                                'unique'    => 'El número de cédula esta duplicado',
                                            ]),

                                        //Email
                                        TextInput::make('email')
                                            ->label('Correo Electrónico')
                                            ->prefixIcon('heroicon-c-at-symbol')
                                            ->email()
                                            ->rules(['email','unique:clientes,email'])
                                            ->validationMessages([
                                                'email'     => 'El campo debe contener el (@)',
                                                'unique'    => 'El correo esta duplicado',
                                            ]),

                                        //Telefono
                                        Select::make('country_code')
                                            ->label('Código de país')
                                            ->options([
                                                '+1' => '🇺🇸 +1 (EE.UU.)',
                                                '+58' => '🇻🇪 +58 (Venezuela)',
                                                '+52' => '🇲🇽 +52 (México)',
                                                '+34' => '🇪🇸 +34 (España)',
                                                '+55' => '🇧🇷 +55 (Brasil)',
                                                '+44' => '🇬🇧 +44 (Reino Unido)',
                                                '+33' => '🇫🇷 +33 (Francia)',
                                                '+49' => '🇩🇪 +49 (Alemania)',
                                            ])
                                            ->searchable()
                                            ->default('+58')
                                            ->required(),
                                            
                                        TextInput::make('telefono')
                                            ->label('Teléfono')
                                            ->tel()
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                                $countryCode = $get('country_code');

                                                if ($countryCode) {
                                                    $cleanNumber = ltrim(preg_replace('/[^0-9]/', '', $state), '0');
                                                    $set('telefono', $countryCode . $cleanNumber);
                                                }
                                            })
                                            ->prefixIcon('heroicon-c-device-phone-mobile')
                                            ->unique(column: 'telefono')
                                            ->rules(['required'])
                                            ->validationMessages([
                                                'required'  => 'Campo requerido',
                                                'unique'    => 'El número de teléfono esta duplicado',
                                            ]),

                                            
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
