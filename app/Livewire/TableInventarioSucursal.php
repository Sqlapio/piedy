<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\Requisicion;
use App\Models\DetalleRequisicion;
use App\Models\InventarioSucursal;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use App\Http\Controllers\LogController;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Collection;
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
            // ->where('accepted_at', null))
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
                TextInputColumn::make('cant_requisicion')
                ->label('Cantidad(Requisicion)')

            ])
            ->defaultGroup('uso')
            ->filters([
                //
            ])
            ->actions([
                Action::make('asignar')
                    ->color('success')
                    ->visible(function (InventarioSucursal $record) {
                        if ($record->uso == 'consumo-interno') {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->label('Asignar Producto')
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
                                        Select::make('user_id')
                                            ->label('Selección del Técnico')
                                            ->prefixIcon('heroicon-c-users')
                                            ->options(User::whereBetween('rol_id', [1, 2])->where('status', 1)->pluck('name', 'id'))
                                            ->rules(['required'])
                                            ->validationMessages([
                                                'required' => 'Debe selecionar un tecnico',
                                            ])
                                            ->searchable(),
                                        TextInput::make('cantidad')
                                            ->label('Cantidad asignada')
                                            ->prefixIcon('heroicon-c-credit-card')
                                            ->hint('Nota: solo números enteros')
                                            ->rules(['required', 'numeric', 'integer'])
                                            ->validationMessages([
                                                'required' => 'Debe introducir la cantidad',
                                                'numeric' => 'Campo numerico',
                                                'integer' => 'Debe ser un numero entero',
                                            ]),
                                    ]),
                            ])
                    ])
                    ->action(function (InventarioSucursal $record, array $data) {
                        InventarioSucursalController::asignar_producto(
                            $data['user_id'],
                            $data['cantidad'],
                            $record->producto_id
                        );
                    })
            ])
            ->bulkActions([
                BulkAction::make('aceptar')
                ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->label('Generar Requisición')
                    ->icon('heroicon-c-cog-8-tooth')
                    ->color('success')
                //     ->form(function (Collection $records) {
                //         foreach ($records as $record) {
                //             return [
                //                 TextInput::make('name');
                //             ]
                //         }
                //     })  
                // ->action(function (array $data, $livewire) {
                //     dd($livewire->selectedTableRecords, $data);
                //     // $data contains the form value, and the $livewire contains many stuff, but for getting only the selected data you can use $livewire->selectedTableRecords 

                // })
                    ->action(function (Collection $records) {

                        try {
                            
                            $codigo = rand(11111, 99999);

                            $records = $records->toArray();

                            //Guardo la informacion de la requision en la tabla de requisicion
                            $requisicion = Requisicion::create([
                                'codigo' => $codigo,
                                'fecha' => date('d-m-Y'),
                                'sucursal_id' => Auth::user()->sucursal_id,
                                'user_id' => Auth::user()->id,
                                'status' => 1,
                            ]);
                            
                            //Fpr para generar el detalle de la riquisicion y guardar la data en la tabla de detalleRequisicion
                            for ($i = 0; $i < count($records); $i++) {
                                //Guardo la informacion en la tabla de detalle de Requisicion
                                $generaDetalle = DetalleRequisicion::create([
                                    'codigo'          => $requisicion['codigo'],
                                    'requisicion_id'  => $requisicion['id'],
                                    'producto_id'     => $records[$i]['producto_id'],
                                    'cantidad'        => $records[$i]['cant_requisicion'],
                                    'sucursal_id'     => $requisicion['sucursal_id'],
                                    'uso'             => $records[$i]['uso'],
                                    'status'          => 1,   
                                    
                                ]);
                            }

                            //For para actualizar el campo cant_requisicion y colocarlo en cero(0)
                            for ($i = 0; $i < count($records); $i++) {
                                //Guardo la informacion en la tabla de detalle de Requisicion
                                $update = InventarioSucursal::where('producto_id',$records[$i]['producto_id'])
                                ->first()
                                ->update([
                                    'cant_requisicion' => 0
                                ]);
                            }
 
                            //Envio una notificacion por whatsaap
                            $notificacion = NotificacionesController::notificacion_requisicion($codigo);
                            
                            if($notificacion['success'] == true){
                                LogController::log(Auth::user()->id, 'requisicion', 'Se creo la requisicion nro: '.$codigo, $response = null);
                                Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-c-x-circle')
                                ->color('success')
                                ->iconColor('success')
                                ->body('La requisicion fue creada con exito')
                                ->send();
                            }
                        } catch (\Throwable $th) {
                            LogController::log(Auth::user()->id, 'excepcion', $th->getMessage(), $response = null);
                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-c-x-circle')
                                ->color('danger')
                                ->iconColor('danger')
                                ->body($th->getMessage())
                                ->send();
                        }
                    }),
            ])->striped();
    }

    public function render(): View
    {
        return view('livewire.table-inventario-sucursal');
    }
}