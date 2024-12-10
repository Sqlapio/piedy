<?php

namespace App\Livewire;

use App\Http\Controllers\InventarioSucursalController;
use App\Models\InventarioSucursal;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Grid;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

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
                    ->color(function(InventarioSucursal $record) {
                        if($record->cantidad < 6){
                            return 'danger';
                        }else{
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
                ->color('success')
                ->visible(function (InventarioSucursal $record) {
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
                    ->action(function (InventarioSucursal $record, array $data) {
                        InventarioSucursalController::asignar_producto(
                            $data['user_id'], 
                            $data['cantidad'], 
                            $record->producto_id
                        );
                    })
                ]);
    }

    public function render(): View
    {
        return view('livewire.table-inventario-sucursal');
    }
}