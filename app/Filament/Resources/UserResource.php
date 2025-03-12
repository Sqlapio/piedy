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
                            ->tel()
                            ->label('Número de teléfono')
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $countryCode = $get('country_code');

                                if ($countryCode) {
                                    $cleanNumber = ltrim(preg_replace('/[^0-9]/', '', $state), '0');
                                    $set('telefono', $countryCode . $cleanNumber);
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
