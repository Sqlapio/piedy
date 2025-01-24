<?php

namespace App\Livewire;

use Filament\Tables;
use Livewire\Component;
use App\Models\Sucursal;
use App\Models\Inventario;
use Filament\Tables\Table;
use App\Models\DetalleRequisicion;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextInputColumn;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\RequisicionController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ModalTableDetalleRequisicion extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $codigo;
    public $sucursal_id;
    public $nombre_sucursal;

    public function mount($codigo, $sucursal_id)
    {
        $this->codigo = $codigo;
        $this->sucursal_id = $sucursal_id;
        $this->nombre_sucursal = Sucursal::where('id', $sucursal_id)->first()->nombre;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('DETALLE DE REQUISICION')
            ->description('Requisicion Nro: ' . $this->codigo . ' - Sucursal: ' . $this->nombre_sucursal)
            ->query(DetalleRequisicion::query()
                ->where('codigo', $this->codigo)
                ->where('sucursal_id', $this->sucursal_id))
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->description(fn ($record) => $record->producto_id)
                    ->weight(FontWeight::Bold)
                    ->numeric()
                    ->sortable()
                    ->color(function ($record) {
                        if($record->existencia == 0){
                            return 'colorDisabled';
                        }
                        if($record->existencia >= 1 || $record->existencia <= 5){
                            return 'dander';
                        }
                    }),
            
                Tables\Columns\TextColumn::make('uso')
                    ->color(function ($record) {
                        if ($record->existencia == 0) {
                            return 'colorDisabled';
                        }
                        if ($record->existencia >= 1 || $record->existencia <= 5) {
                            return 'dander';
                        }
                    })
                    ->searchable(),
                TextInputColumn::make('cantidad')
                    ->label('Cantidad Solicitada')
                    ->disabled(function ($record) {
                        if ($record->existencia == 0) {
                            return 'true';
                        }
                    })  
                    ->sortable(),
                Tables\Columns\TextColumn::make('existencia')
                    ->label('Existencia')
                    ->color(function ($record) {
                        if ($record->existencia == 0) {
                            return 'colorDisabled';
                        }
                        if ($record->existencia >= 1 || $record->existencia <= 5) {
                            return 'dander';
                        }
                    })
                    ->alignCenter()
                    ->grow(false)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('almacen.nombre')
                    ->label('Ubicación')
                    ->color(function ($record) {
                        if ($record->existencia == 0) {
                            return 'colorDisabled';
                        }
                        if ($record->existencia >= 1 || $record->existencia <= 5) {
                            return 'dander';
                        }
                    })
                    ->alignCenter()
                    ->grow(false)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('costo')
                    ->label('Costo($)')
                    ->color(function ($record) {
                        if ($record->existencia == 0) {
                            return 'colorDisabled';
                        }
                        if ($record->existencia >= 1 || $record->existencia <= 5) {
                            return 'dander';
                        }
                    })
                    ->alignCenter()
                    ->money('USD')
                    ->summarize(
                        Sum::make()
                        ->money('USD')
                        ->label('Total($)')
                    )
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
            //
            ])
            ->headerActions([
            Tables\Actions\Action::make('Añadir Producto')
            ->icon('heroicon-c-arrow-uturn-down')
            // ->iconColor('success')
            ->form([
                Section::make('Formulario')
                ->description('Formulario para sustir un producto dentro de la requisicion')
                ->relationship('requisicion')
                    ->icon('heroicon-s-clipboard-document-list')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Select::make('producto_id')
                                    ->label('Producto')
                                    ->options(function () {
                                        $productos = DB::table('inventarios')
                                            ->select(DB::raw('producto_id as id, productos.descripcion as descripcion'))
                                            ->where('cantidad', '>', 0)
                                            ->join('productos', 'inventarios.producto_id', '=', 'productos.id')
                                            ->groupBy('producto_id')
                                            ->get();

                                        return $productos->pluck('id', 'id');
                                    })
                                    ->rules(['required'])
                                    ->validationMessages([
                                        'required' => 'Debe selecionar un producto de la lista',
                                    ]),
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

                            ]),
                    ])
            ])
                ->action(function (DetalleRequisicion $record, array $data) {

                    $update = RequisicionController::updateDetalleRequisicion($data, $record, $this->codigo);

                    if ($update) {
                        $this->resetTable();
                    }
                })
            ])
            ->bulkActions([
                BulkAction::make('aceptar')
                ->requiresConfirmation()
                ->deselectRecordsAfterCompletion()
                ->label('Aceptar Requisición')
                ->icon('heroicon-c-cog-8-tooth')
                ->color('success')
            ])
            ->checkIfRecordIsSelectableUsing(fn(DetalleRequisicion $record): bool => $record->existencia > 0)
            ->striped();
    }

    public function render(): View
    {
        return view('livewire.modal-table-detalle-requisicion');
    }
}