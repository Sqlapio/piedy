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
use App\Models\ManejoEfectivo;
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
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\RequisicionController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Controllers\ManejoEfectivoController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\InventarioSucursalController;
use App\Http\Controllers\ManejoEfectivoDetalleController;

class TableManejoEfectivo extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('MANEJO DEL EFECTIVO')
            ->description('Tabla donde se registran los retiros del efectivo en dolares en tienda')
            ->query(ManejoEfectivo::query())
            ->columns([

                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Retiro')
                    ->alignCenter()
                    ->dateTime(),

                Tables\Columns\TextColumn::make('responsable')
                    ->label('Retirado por:')
                    ->alignCenter()
                    ->searchable(),

                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto Retirado($)')
                    ->money('USD')
                    ->alignCenter()
                    ->summarize(Sum::make()
                    ->money('USD')
                    ->label('Total pagar($)')),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Registro de recepcion de Efectivo')
                    ->color('success')
                    ->form([
                        Section::make('Cierres en Efectivo')
                        ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                        ->icon('heroicon-c-users')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    Repeater::make('detalles')
                                    ->schema([
                                        Grid::make()
                                            ->schema([
                                                DatePicker::make('fecha')
                                                    ->label('Fecha del Cierre')
                                                    ->prefixIcon('heroicon-m-calendar-days')
                                                    ->required()     
                                                    ->format('d-m-Y'),
                                                    
                                                TextInput::make('resposables')
                                                    ->label('Recibido Por:')
                                                    ->prefixIcon('heroicon-c-credit-card')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->default(Auth::user()->name),
                                            ])->columns(2),

                                        Grid::make()
                                            ->schema([
                                                TextInput::make('monto')
                                                    ->label('Monto del Cierre ($)')
                                                    ->prefixIcon('heroicon-c-credit-card')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::updateTotales($get, $set);
                                                    })
                                                    ->placeholder('0.00'),
                                                    
                                                TextInput::make('deduccion')
                                                    ->label('Deduccion')
                                                    ->live(onBlur: true)
                                                    ->prefixIcon('heroicon-c-credit-card')
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::updateTotales($get, $set);
                                                    })
                                                    ->placeholder('0.00'),
                                                    
                                                TextInput::make('total')
                                                    ->label('Monto Total Recibido ($)')
                                                    ->prefixIcon('heroicon-c-credit-card')
                                                    ->live()
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->numeric()
                                                    ->default(0.00),
                                                    
                                                Textarea::make('observacion')->rows(1)->columnSpanFull()
                                            ])->columns(3),
                                            
                                    ])->columnSpanFull(),
                                ])->columns(3),
                        ])->collapsible(),
                    ])
                    ->action(function (array $data) {
                        
                        $monto_total = 0;
                        
                        for($i = 0; $i < count($data['detalles']); $i++) {
                            $monto_total += $data['detalles'][$i]['total']; 
                        }
                        // dd($monto_total);

                        $asiento_id = ManejoEfectivoController::crear_asiento(Auth::user()->name, $monto_total);

                        for($i = 0; $i < count($data['detalles']); $i++) {
                            
                            //creamos el detalle del asiento 
                            ManejoEfectivoDetalleController::crear_detalle(
                                $asiento_id, 
                                Auth::user()->sucursal_id, 
                                Auth::user()->name, 
                                $data['detalles'][$i]['monto'], 
                                $data['detalles'][$i]['deduccion'], 
                                $data['detalles'][$i]['fecha'], 
                                $data['detalles'][$i]['observacion'], 
                                $data['detalles'][$i]['total']
                            );
                        }
                        
                        if($asiento_id > 0 ) {
                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-c-check-circle')
                                ->color('success')
                                ->iconColor('success')
                                ->body('El registrio fue creado con exito.')
                                ->send();
                        }
                    })
                    // ->modalWidth(MaxWidth::TwoExtraLarge)
                    ->hidden(fn() => Auth::user()->rol_id != 5 && Auth::user()->rol_id != 7),
                    // ->slideOver(),
            ])->striped()
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public static function updateTotales(Get $get, Set $set): void
    {

        if ($get('deduccion') != null) {
            $set('total', round($get('monto') - $get('deduccion'), 2));
        }else{
            $set('total', round($get('monto'), 2));
        }
    }

    public function render(): View
    {
        return view('livewire.table-manejo-efectivo');
    }
}