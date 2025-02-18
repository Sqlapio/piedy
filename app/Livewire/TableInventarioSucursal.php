<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Tables;
use App\Models\Cliente;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Inventario;
use Filament\Tables\Table;
use App\Models\Requisicion;
use Filament\Support\RawJs;
use App\Models\DetalleRequisicion;
use App\Models\InventarioSucursal;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use App\Http\Controllers\LogController;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\ClienteController;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\RequisicionController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\InventarioSucursalController;

class TableInventarioSucursal extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('INVENTARIO GENERAL')
            ->description('Tabla de inventario para los productos de venta y de consumo interno')
            ->query(InventarioSucursal::query()
                ->where('sucursal_id', Auth::user()->sucursal_id))
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->icon('heroicon-s-truck')
                    ->searchable(),

                Tables\Columns\TextColumn::make('uso')
                    ->icon('heroicon-s-megaphone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Exitencia actual')
                    ->alignCenter()
                    ->icon('heroicon-m-square-3-stack-3d')
                    ->color(function (InventarioSucursal $record) {
                        if ($record->cantidad < 6) {
                            return 'danger';
                        } else {
                            return 'success';
                        }
                    }),

            ])
            ->defaultGroup('uso')
            ->filters([
                //
            ])
            ->actions([
                Action::make('asignar')
                    ->label('Asignar Producto')
                    ->color('success')
                    ->visible(function (InventarioSucursal $record) {
                        if ($record->uso == 'consumo-interno') {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->icon('heroicon-s-user-plus')
                    ->form([
                        Section::make('Formulario')
                            ->description(function (InventarioSucursal $record) {
                                return 'Producto para asignar: ' . $record->producto->descripcion;
                            })
                            ->icon('heroicon-s-clipboard-document-list')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        ToggleButtons::make('feedback')
                                            ->label('El producto es asignado a:?')
                                            // ->hidden(fn() => Auth::user()->rol_id != 5 && Auth::user()->rol_id != 7)
                                            ->options([
                                                'tecnico' => 'Tecnico',
                                                'tienda' => 'Tienda',
                                            ])
                                            ->options(function (Get $get) {
                                                if(Auth::user()->rol_id == 5 || Auth::user()->rol_id == 7){
                                                    return [
                                                        'tecnico' => 'Tecnico',
                                                        'tienda' => 'Tienda',
                                                    ];
                                                }else{
                                                    return [
                                                        'tecnico' => 'Tecnico',
                                                    ];
                                                }
                                            })
                                            ->colors([
                                                'tienda' => 'info',
                                                'tecnico' => 'success',
                                            ])
                                            ->icons([
                                                'tienda' => 'heroicon-o-pencil',
                                                'tecnico' => 'heroicon-o-clock',
                                            ])
                                            ->columnSpanFull()
                                            ->live()
                                            ->inline(),
                                            
                                            //Seccion para asignar al tecnico
                                            //--------------------------------------------------------------
                                            Section::make()
                                            ->hidden(fn (Get $get) => $get('feedback') != 'tecnico')
                                            ->schema([
                                                Select::make('user_id')
                                                    ->label('Selección del Técnico')
                                                    ->prefixIcon('heroicon-c-users')
                                                    ->options(User::whereBetween('rol_id', [1, 2])->where('status', 1)->pluck('name', 'id'))
                                                    // ->hidden()
                                                    ->searchable(),
                                                TextInput::make('cantidad')
                                                    ->label('Cantidad asignada')
                                                    ->prefixIcon('heroicon-c-credit-card')
                                                    ->hint('Nota: solo números enteros')
                                                    ->required()
                                                    ->rules(['required', 'numeric', 'integer'])
                                                    ->validationMessages([
                                                        'required' => 'Debe introducir la cantidad',
                                                        'numeric' => 'Campo numerico',
                                                        'integer' => 'Debe ser un número entero',
                                                    ]),
                                            ]),

                                            //Seccion para asignar a la tienda
                                            //--------------------------------------------------------------
                                            Section::make()
                                            ->hidden(fn (Get $get) => $get('feedback') != 'tienda')
                                            ->schema([
                                                Select::make('sucursal')
                                                    ->label('Selección de Sucursal')
                                                    ->prefixIcon('heroicon-c-users')
                                                    ->options(function () {
                                                        $user_auth = Auth::user()->sucursal_id;
                                                        return Sucursal::where('id', $user_auth)->pluck('nombre', 'nombre');
                                                    })
                                                    ->searchable(),
                                                TextInput::make('cantidad')
                                                    ->label('Cantidad asignada')
                                                    ->prefixIcon('heroicon-c-credit-card')
                                                    ->hint('Nota: solo números enteros')
                                                    ->required()
                                                    ->rules(['required', 'numeric', 'integer'])
                                                    ->validationMessages([
                                                        'required' => 'Debe introducir la cantidad',
                                                        'numeric' => 'Campo numerico',
                                                        'integer' => 'Debe ser un número entero',
                                                    ]),
                                            ]),
                                    ]),
                            ])
                    ])
                    ->action(function (InventarioSucursal $record, array $data) {
                        InventarioSucursalController::asignar_producto($data, $record->producto_id);
                    })
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Crear Requisicion')
                    ->color('success')
                    ->model(Requisicion::class)
                    ->form([
                        Section::make('Formulario de Requisicion')
                            ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                            ->icon('heroicon-c-users')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        //codigo de requisicion
                                        TextInput::make('codigo')
                                            ->label('Codigo de Requisicion')
                                            ->prefixIcon('heroicon-c-users')
                                            ->required()
                                            ->readOnly()
                                            ->default('REQ-' . random_int(111111, 999999)),
                                    ]),
                            ]),
                            
                        Section::make('Productos para requisicion')
                            ->icon('heroicon-c-users')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        Repeater::make('productos')
                                            ->schema([
                                                Grid::make()
                                                    ->schema([
                                                        Select::make('producto_id')
                                                            ->label('Producto')
                                                            ->options(Producto::all()->pluck('descripcion', 'id'))
                                                            ->searchable()
                                                            ->preload()
                                                            ->relationship(
                                                                name: 'producto',
                                                                modifyQueryUsing: fn(Builder $query) => $query->orderBy('descripcion'),
                                                            )
                                                            ->createOptionForm([
                                                                Section::make('Formulario')
                                                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                                                ->icon('heroicon-c-users')
                                                                ->schema([
                                                                    Grid::make()
                                                                        ->schema([

                                                                            //Nombre y apellido
                                                                            TextInput::make('cod_producto')
                                                                            ->label('Codigo de Producto')
                                                                            ->prefixIcon('heroicon-c-users')
                                                                            ->required()
                                                                            ->readOnly()
                                                                            ->default('Ppro-' . random_int(11111, 99999)),

                                                                            //Cedula
                                                                            TextInput::make('descripcion')
                                                                            ->label('Descripcion')
                                                                            ->prefixIcon('heroicon-c-credit-card')
                                                                            ->lazy()
                                                                            ->afterStateUpdated(fn (string $state, Set $set) => $set('descripcion', strtoupper($state)))
                                                                            ->required(),

                                                                            Select::make('uso')
                                                                            ->prefixIcon('heroicon-s-inbox-arrow-down')
                                                                            ->label('Uso')
                                                                            ->options([
                                                                                'consumo-interno' => 'Consumo Interno',
                                                                                'venta' => 'Venta',
                                                                            ])->required(),

                                                                            TextInput::make('contenido_neto')
                                                                            ->prefixIcon('heroicon-m-list-bullet')
                                                                            ->label('Contenido Neto')
                                                                            // ->required()
                                                                            ->numeric(),

                                                                            Select::make('unidad')
                                                                            ->prefixIcon('heroicon-m-list-bullet')
                                                                            ->label('Unidad')
                                                                            // ->required()
                                                                            ->options([
                                                                                'gr'        => 'Gramos',
                                                                                'ml'        => 'Mililitros',
                                                                                'oz'        => 'Onzas',
                                                                                'par'       => 'Pares',
                                                                                'pzas'      => 'Piezas',
                                                                                'hojas'     => 'Hojas',
                                                                                'und'       => 'Unidad',
                                                                                'litros'    => 'Litros',
                                                                                'galon'     => 'Galon',
                                                                                'kl'        => 'Kilos',
                                                                            ]),
                                                                            
                                                                            TextInput::make('responsable')
                                                                            ->prefixIcon('heroicon-m-list-bullet')
                                                                            ->label('Creado por:')
                                                                            ->default(Auth::user()->name)
                                                                            ->readOnly(),

                                                                        ]),
                                                                ])
                                                            ])
                                                            ->rules(['required'])
                                                            ->getOptionLabelFromRecordUsing(fn(Producto $record) => "{$record->descripcion} - {$record->contenido_neto}{$record->unidad}")
                                                            ->validationMessages([
                                                                'required' => 'Debe selecionar un producto de la lista',
                                                            ]),
                                                            // ->relationship(name: 'author', titleAttribute: 'name')
                                                            
                                                        TextInput::make('cantidad')
                                                            ->label('Cantidad')
                                                            ->prefixIcon('heroicon-c-credit-card')
                                                            ->hint('Nota: solo números enteros')
                                                            ->rules(['required', 'numeric', 'integer'])
                                                            ->validationMessages([
                                                                'required' => 'Debe selecionar un tecnico',
                                                                'numeric' => 'Campo numerico',
                                                                'integer' => 'Debe ser un número entero',
                                                            ])
                                                            ->default(1),
                                                        Textarea::make('observacion')->rows(1)->columnSpanFull()
                                                    ])
                                            ])->columnSpanFull(),
                                    ])->columns(3),
                            ])->collapsible(),
                    ])
                    ->action(function (array $data) {
                        $crearRequisicion = RequisicionController::crearRequisicion($data);
                        if ($crearRequisicion) {

                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-c-x-circle')
                                ->color('success')
                                ->iconColor('success')
                                ->body('La requisicion fue creada con éxito')
                                ->send();

                            //Envio una notificacion por whatsaap
                            $notificacion = NotificacionesController::notificacion_requisicion($data['codigo'], Auth::user()->sucursal_id);

                            if ($notificacion['success'] == true) {
                                LogController::log(Auth::user()->id, 'Notificacion-requisicion', 'Se creo la requisición nro: ' . $data['codigo'], $response = null);
                                Notification::make()
                                    ->title('NOTIFICACIÓN')
                                    ->icon('heroicon-c-x-circle')
                                    ->color('success')
                                    ->iconColor('success')
                                    ->body('La notificacion fue enviada via whatsapp con éxito')
                                    ->send();
                            }
                        }
                    })
                    ->modalWidth(MaxWidth::TwoExtraLarge)
                    ->hidden(fn() => Auth::user()->rol_id != 5 && Auth::user()->rol_id != 7)
                    ->slideOver(),
            ])->striped();
    }

    public function render(): View
    {
        return view('livewire.table-inventario-sucursal');
    }
}