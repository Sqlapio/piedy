<?php

namespace App\Livewire;

use App\Models\RecepcionInventario;
use App\Models\InventarioSucursal;
use App\Http\Controllers\InventarioSucursalController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use App\Models\User;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Collection;

class TableRecepcionInventario extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('INVENTARIO')
            ->description('Tabla de gestion del inventario de ventas y de consumo interno')
            ->query(RecepcionInventario::query())
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->numeric()
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'success';
                        }else{
                            return 'colorDisabled';
                        }
                    })
                    ->icon(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'heroicon-s-document-check';
                        }else{
                            return 'heroicon-m-lock-closed';
                        }
                    }),
                Tables\Columns\TextColumn::make('uso')
                    ->icon('heroicon-s-megaphone')
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'colorOne';
                        }else{
                            return 'colorDisabled';
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Existencia')
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'warning';
                        }else{
                            return 'colorDisabled';
                        }
                    })
                    ->icon('heroicon-c-rectangle-stack'),

                Tables\Columns\TextColumn::make('responsable')
                    ->label('Asignado por:')
                    ->color('success')
                    ->icon('heroicon-c-user-circle'),
                    
                Tables\Columns\TextColumn::make('accepted_at')
                    ->label('Aceptado el:')
                    ->color(function (RecepcionInventario $record) {
                        if($record->accepted_at !== null){
                            return 'colorTree';
                        }else{
                            return 'colorDisabled';
                        }
                    })
                    ->date()
                    ->icon('heroicon-c-calendar-days'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('asignar')
                ->color('success')
                ->visible(function (RecepcionInventario $record) {
                    if($record->uso == 'consumo-interno'){
                        return true;
                    }else{
                        return false;
                    }
                })
                ->label('Asignar Producto')
                ->icon('heroicon-s-user-plus')
                    ->form([
                        Section::make('Formulario')
                                ->description(function (RecepcionInventario $record) {
                                    return 'Producto para asignar: ' . $record->producto->descripcion;
                                })
                                ->icon('heroicon-s-clipboard-document-list')
                                ->schema([
                                    Grid::make()
                                    ->schema([
                                        Select::make('user_id')
                                        ->label('Selección del Técnico')
                                        ->prefixIcon('heroicon-c-users')
                                        ->options(User::whereBetween('rol_id', [1,2])->where('status', 1)->pluck('name', 'id'))
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
                    ->action(function (RecepcionInventario $record, array $data) {
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
                ->label('Aceptación de Inventario')
                ->icon('heroicon-c-cog-8-tooth')
                ->color('success')
                ->action(function (Collection $records) {
                    
                    foreach ($records as $record) {
                        
                        $producto_aceptado = RecepcionInventario::find($record->id);
                        
                        $producto_existe = InventarioSucursal::where('producto_id', $producto_aceptado->producto_id)
                        ->where('sucursal_id', auth()->user()->sucursal_id)
                        ->first();
                        
                        if(isset($producto_existe)){
                            $producto_existe->cantidad += $producto_aceptado->cantidad;
                            $producto_existe->accepted_at = now();
                            $producto_existe->save();
                            
                        }else{
                            InventarioSucursal::create([
                                'producto_id'   => $producto_aceptado->producto_id,
                                'sucursal_id'   => auth()->user()->sucursal_id,
                                'cantidad'      => $producto_aceptado->cantidad,
                                'responsable'   => auth()->user()->name,
                                'uso'           => $producto_aceptado->uso,
                                'aceptado_por'  => auth()->user()->name,
                                ]);

                            $producto_aceptado->accepted_at = now();
                            $producto_aceptado->save();
                        }
                        
                    }
                }),
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-recepcion-inventario');
    }
}