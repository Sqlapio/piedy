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
            ->heading('INVENTARIO')
            ->description('Tabla de gestion del inventario de ventas y de consumo interno')
            ->query(InventarioSucursal::query()
            ->where('sucursal_id', Auth::user()->sucursal_id))
            // ->where('accepted_at', null))
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->numeric()
                    ->color(function (InventarioSucursal $record) {
                        if($record->accepted_at !== null){
                            return 'success';
                        }else{
                            return 'colorDisabled';
                        }
                    })
                    ->icon(function (InventarioSucursal $record) {
                        if($record->accepted_at !== null){
                            return 'heroicon-s-document-check';
                        }else{
                            return 'heroicon-m-lock-closed';
                        }
                    }),

                Tables\Columns\TextColumn::make('uso')
                ->icon('heroicon-s-megaphone')
                ->color(function (InventarioSucursal $record) {
                    if($record->accepted_at !== null){
                        return 'colorOne';
                    }else{
                        return 'colorDisabled';
                    }
                })
                ->searchable(),

                Tables\Columns\TextColumn::make('cantidad')
                ->label('Existencia')
                ->color(function (InventarioSucursal $record) {
                    if($record->accepted_at !== null){
                        return 'warning';
                    }else{
                        return 'colorDisabled';
                    }
                })
                ->icon('heroicon-c-rectangle-stack')
                ->numeric(),

                Tables\Columns\TextColumn::make('accepted_at')
                    ->label('Aceptado el:')
                    ->color(function (InventarioSucursal $record) {
                    if($record->accepted_at !== null){
                        return 'colorTree';
                    }else{
                        return 'colorDisabled';
                    }
                })
                    ->date()
                    ->icon('heroicon-c-calendar-days'),

                Tables\Columns\TextColumn::make('aceptado_por')
                ->label('Aceptado por:')
                ->searchable()
                ->color(function (InventarioSucursal $record) {
                    if($record->accepted_at !== null){
                        return 'colorTree';
                    }else{
                        return 'colorDisabled';
                    }
                })
                ->icon('heroicon-m-user-circle'),
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
                        $aceptado = InventarioSucursal::find($record->id);
                        $aceptado->accepted_at = now();
                        $aceptado->aceptado_por = auth()->user()->name;
                        $aceptado->save();
                    }
                }),
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-inventario-sucursal');
    }
}
