<?php

namespace App\Livewire;

use App\Http\Controllers\AsignacionController;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\DetalleAsignacion;
use App\Models\InventarioSucursal;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\Summarizers\Sum;

class TableDetalleAsignacion extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $cod_asignacion;
    public $cliente_id;

    public function mount($cod_asignacion, $cliente_id)
    {
        $this->cod_asignacion = $cod_asignacion;
        $this->cliente_id = $cliente_id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Asignaciones')
            ->query(DetalleAsignacion::query()->where('cod_asignacion', $this->cod_asignacion))
            ->columns([
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo de Asiganción')
                    ->description(fn (DetalleAsignacion $record): string => ($record->servicio_id != null) ? Servicio::find($record->servicio_id)->descripcion : Producto::find($record->producto_id)->descripcion)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'servicio' => 'success',
                        'producto' => 'warning',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'servicio' => 'heroicon-o-swatch',
                        'producto' => 'heroicon-o-shopping-cart',
                    }),
                Tables\Columns\TextColumn::make('costo')
                    ->label('Costo($)')
                    ->money('USD')
                    ->summarize(Sum::make()
                        ->money('USD')
                        ->label('Total pagar($)')
                    )
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('eliminar')
                ->requiresConfirmation()
                ->action(fn (DetalleAsignacion $record) => $record->delete())
                ->icon('heroicon-c-trash')
                ->color('danger')
                //UI - Modal
                ->modalIcon('heroicon-m-shopping-cart')
                ->modalHeading('Eliminar Item')
                ->modalDescription('Estas seguro que desea eliminar el item')
                ->modalSubmitActionLabel('Si, eliminar item!')
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->headerActions([
                ActionGroup::make([
                    Action::make('Añadir Servicios')
                    ->label('Añadir Servicios')
                    ->icon('heroicon-c-document-plus')
                    ->color('success')
                    ->hidden(! (auth()->user()->tipo_usuario == 'empleado'))
                    ->model(DetalleAsignacion::class)
                        ->form([
                            Section::make('Agregar Servicio')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-o-swatch')
                                ->schema([
                                    //Seleccion de servicio
                                    Select::make('servicio_id')
                                    ->label('Servicios')
                                    ->prefixIcon('heroicon-o-swatch')
                                    ->options(Servicio::where('sucursal_id', Auth::user()->sucursal_id)->pluck('descripcion', 'id'))
                                    ->searchable()
                                    ->required(),
                                ])
                        ])->action(function (array $data) {
                                AsignacionController::asigna_servicio_adicional(
                                    $data['servicio_id'],
                                    $this->cod_asignacion,
                                    $this->cliente_id
                                );
                        }),
    
                    Action::make('Añadir Productos')
                    ->label('Añadir Productos')
                    ->icon('heroicon-c-document-plus')
                    ->color('success')
                    ->model(DetalleAsignacion::class)
                        ->form([
                            Section::make('Agregar Producto')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-o-shopping-cart')
                                ->schema([
                                    //Seleccion de servicio
                                    Select::make('producto_id')
                                    ->label('Productos')
                                    ->prefixIcon('heroicon-o-shopping-cart')
                                    ->options(InventarioSucursal::all()->where('sucursal_id', Auth::user()->sucursal_id)->where('cantidad', '>', 0)->pluck('producto.descripcion', 'id'))
                                    ->searchable()
                                    ->required(),
                                ])
                        ])->action(function (array $data) {
                            AsignacionController::asigna_producto(
                                $data['producto_id'],
                                $this->cod_asignacion,
                                $this->cliente_id
                            );
                        }),
    
                    Action::make('cerrar')
                    ->label('Cerrar Servicio')
                    ->icon('heroicon-c-document-plus')
                    ->color('danger')
                    ->hidden(! (auth()->user()->tipo_usuario == 'empleado'))
                    ->model(DetalleAsignacion::class)
                        ->form([
                            Section::make('Contraseña')
                                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                                ->icon('heroicon-c-finger-print')
                                ->schema([
                                    //Seleccion de servicio
                                    TextInput::make('clave')
                                    ->label('Contraseña')
                                    ->prefixIcon('heroicon-c-finger-print')
                                    ->password()
                                    ->revealable()
                                    ->required(),
                                ])
                        ])->action(function (array $data) {
                            AsignacionController::cerrar_servicio(
                                $data['clave'],
                                $this->cod_asignacion,
                            );
                        }),
                ])
                ->label('Menú')
                ->icon('heroicon-c-adjustments-horizontal')
                ->size(ActionSize::Small)
                ->color('success')
                ->button()
            ]);
    }

    public function render(): View
    {
        return view('livewire.table-detalle-asignacion');
    }
}
