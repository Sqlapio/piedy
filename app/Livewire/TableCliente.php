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
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
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

    public $query;

    public function mount()
    {
        Cache::remember('clientes', 1200, function () {
            return DB::table('clientes')->select('id', 'nombre', 'cedula', 'telefono', 'email')->orderBy('id', 'desc')->get();
        });
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('CLIENTES')
            ->description('Tabla de gestión de clientes')
            ->query(Cliente::select('id', 'nombre', 'cedula', 'telefono', 'email')->orderBy('id', 'desc'))
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
                                                '+1'   => '🇺🇸 +1 (Estados Unidos)',
                                                '+44'  => '🇬🇧 +44 (Reino Unido)',
                                                '+49'  => '🇩🇪 +49 (Alemania)',
                                                '+33'  => '🇫🇷 +33 (Francia)',
                                                '+34'  => '🇪🇸 +34 (España)',
                                                '+39'  => '🇮🇹 +39 (Italia)',
                                                '+7'   => '🇷🇺 +7 (Rusia)',
                                                '+55'  => '🇧🇷 +55 (Brasil)',
                                                '+91'  => '🇮🇳 +91 (India)',
                                                '+86'  => '🇨🇳 +86 (China)',
                                                '+81'  => '🇯🇵 +81 (Japón)',
                                                '+82'  => '🇰🇷 +82 (Corea del Sur)',
                                                '+52'  => '🇲🇽 +52 (México)',
                                                '+58'  => '🇻🇪 +58 (Venezuela)',
                                                '+57'  => '🇨🇴 +57 (Colombia)',
                                                '+54'  => '🇦🇷 +54 (Argentina)',
                                                '+56'  => '🇨🇱 +56 (Chile)',
                                                '+51'  => '🇵🇪 +51 (Perú)',
                                                '+502' => '🇬🇹 +502 (Guatemala)',
                                                '+503' => '🇸🇻 +503 (El Salvador)',
                                                '+504' => '🇭🇳 +504 (Honduras)',
                                                '+505' => '🇳🇮 +505 (Nicaragua)',
                                                '+506' => '🇨🇷 +506 (Costa Rica)',
                                                '+507' => '🇵🇦 +507 (Panamá)',
                                                '+593' => '🇪🇨 +593 (Ecuador)',
                                                '+592' => '🇬🇾 +592 (Guyana)',
                                                '+591' => '🇧🇴 +591 (Bolivia)',
                                                '+598' => '🇺🇾 +598 (Uruguay)',
                                                '+20'  => '🇪🇬 +20 (Egipto)',
                                                '+27'  => '🇿🇦 +27 (Sudáfrica)',
                                                '+234' => '🇳🇬 +234 (Nigeria)',
                                                '+212' => '🇲🇦 +212 (Marruecos)',
                                                '+971' => '🇦🇪 +971 (Emiratos Árabes)',
                                                '+92'  => '🇵🇰 +92 (Pakistán)',
                                                '+880' => '🇧🇩 +880 (Bangladesh)',
                                                '+62'  => '🇮🇩 +62 (Indonesia)',
                                                '+63'  => '🇵🇭 +63 (Filipinas)',
                                                '+66'  => '🇹🇭 +66 (Tailandia)',
                                                '+60'  => '🇲🇾 +60 (Malasia)',
                                                '+65'  => '🇸🇬 +65 (Singapur)',
                                                '+61'  => '🇦🇺 +61 (Australia)',
                                                '+64'  => '🇳🇿 +64 (Nueva Zelanda)',
                                                '+90'  => '🇹🇷 +90 (Turquía)',
                                                '+375' => '🇧🇾 +375 (Bielorrusia)',
                                                '+372' => '🇪🇪 +372 (Estonia)',
                                                '+371' => '🇱🇻 +371 (Letonia)',
                                                '+370' => '🇱🇹 +370 (Lituania)',
                                                '+48'  => '🇵🇱 +48 (Polonia)',
                                                '+40'  => '🇷🇴 +40 (Rumania)',
                                                '+46'  => '🇸🇪 +46 (Suecia)',
                                                '+47'  => '🇳🇴 +47 (Noruega)',
                                                '+45'  => '🇩🇰 +45 (Dinamarca)',
                                                '+41'  => '🇨🇭 +41 (Suiza)',
                                                '+43'  => '🇦🇹 +43 (Austria)',
                                                '+31'  => '🇳🇱 +31 (Países Bajos)',
                                                '+32'  => '🇧🇪 +32 (Bélgica)',
                                                '+353' => '🇮🇪 +353 (Irlanda)',
                                                '+375' => '🇧🇾 +375 (Bielorrusia)',
                                                '+380' => '🇺🇦 +380 (Ucrania)',
                                                '+994' => '🇦🇿 +994 (Azerbaiyán)',
                                                '+995' => '🇬🇪 +995 (Georgia)',
                                                '+976' => '🇲🇳 +976 (Mongolia)',
                                                '+998' => '🇺🇿 +998 (Uzbekistán)',
                                                '+84'  => '🇻🇳 +84 (Vietnam)',
                                                '+856' => '🇱🇦 +856 (Laos)',
                                                '+374' => '🇦🇲 +374 (Armenia)',
                                                '+965' => '🇰🇼 +965 (Kuwait)',
                                                '+966' => '🇸🇦 +966 (Arabia Saudita)',
                                                '+972' => '🇮🇱 +972 (Israel)',
                                                '+963' => '🇸🇾 +963 (Siria)',
                                                '+961' => '🇱🇧 +961 (Líbano)',
                                                '+960' => '🇲🇻 +960 (Maldivas)',
                                                '+992' => '🇹🇯 +992 (Tayikistán)',
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