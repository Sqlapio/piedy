<?php

namespace App\Filament\Resources\RequisicionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Producto;
use Filament\Forms\Form;
use App\Models\Inventario;
use Filament\Tables\Table;
use Tables\Actions\Action;
use App\Models\Requisicion;
use App\Models\DetalleRequisicion;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use App\Http\Controllers\LogController;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\RequisicionController;
use App\Http\Controllers\NotificacionesController;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

// use Filament\Forms\Components\TagsInput;

class DetalleRequisicionRelationManager extends RelationManager
{
    protected static string $relationship = 'detalleRequisicion';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('codigo')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('codigo')
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->description(fn($record) => $record->observacion)
                    ->weight(FontWeight::Bold)
                    ->numeric()
                    ->sortable(),
                    // ->color(function ($record) {
                    //     if ($record->existencia == 0) {
                    //         return 'colorDisabled';
                    //     }
                    //     if ($record->existencia >= 1 || $record->existencia <= 5) {
                    //         return 'dander';
                    //     }
                    // }),

                Tables\Columns\TextColumn::make('uso')
                    // ->color(function ($record) {
                    //     if ($record->existencia == 0) {
                    //         return 'colorDisabled';
                    //     }
                    //     if ($record->existencia >= 1 || $record->existencia <= 5) {
                    //         return 'dander';
                    //     }
                    // })
                    ->searchable(),
                Tables\Columns\TextColumn::make('contenido')
                    ->alignCenter()
                    // ->color(function ($record) {
                    //     if ($record->existencia == 0) {
                    //         return 'colorDisabled';
                    //     }
                    //     if ($record->existencia >= 1 || $record->existencia <= 5) {
                    //         return 'dander';
                    //     }
                    // })
                    ->searchable(),
                TextInputColumn::make('cantidad')
                    ->label('Cantidad Solicitada')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->sub_total = $state * $record->costo;
                        $record->save();
                        //log
                        LogController::log(Auth::user()->id, 'update pre-nomina', 'agrego propina en dolares: ' . $state, $response = null);
                    })
                    ->alignCenter()
                    // ->disabled(function ($record) {
                    //     if ($record->existencia == 0) {
                    //         return 'true';
                    //     }
                    // })
                    ->sortable(),
                Tables\Columns\TextColumn::make('costo')
                    ->label('Costo($)')
                    ->alignCenter()
                    ->money('USD')
                    ->sortable(),

            Tables\Columns\TextColumn::make('sub_total')
                ->label('SubTotal($)')
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
                Tables\Actions\DeleteAction::make(),
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('Añadir Producto')
                    ->label('Actualizar Requisicion')
                    ->color('success')
                    ->model(DetalleRequisicion::class)
                    ->form([
                        Section::make('Formulario de Requisicion')
                        ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                        ->icon('heroicon-c-users')
                        ->schema([
                            Grid::make()
                                ->schema([

                                // codigo de requisicion
                                    Select::make('codigo')
                                    ->label('Nro. de Requisicion')
                                    ->options(Requisicion::where('status_id', 5)->pluck('codigo', 'codigo'))
                                    ->searchable()
                                    ->required()

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
                                                        // ->options(Inventario::where('cantidad', '>', 0)->pluck('producto_id', 'producto_id'))
                                                        // ->searchable()
                                                        // ->options(function () {
                                                        //     $productos = DB::table('inventarios')
                                                        //         ->select(DB::raw('producto_id as id, productos.descripcion as descripcion'))
                                                        //         ->join('productos', 'inventarios.producto_id', '=', 'productos.id')
                                                        //         ->groupBy('producto_id')
                                                        //         ->get();
                                                                
                                                        //     return $productos->pluck('descripcion', 'id');
                                                        // })
                                                        ->relationship(
                                                            name: 'producto',
                                                            modifyQueryUsing: fn(Builder $query) => $query->orderBy('descripcion'),
                                                        )
                                                        ->rules(['required'])
                                                        // ->getOptionLabelFromRecordUsing(fn(Producto $record) => "{$record->descripcion} - {$record->contenido_neto}{$record->unidad}")
                                                        ->getOptionLabelFromRecordUsing(function (Producto $record) {
                                                            $exitencia = $record->inventario->cantidad ?? $record->codigo.'(Debe ingresar al inventario)';
                                                            return "{$record->descripcion} - {$record->contenido_neto}{$record->unidad} - Existencia: {$exitencia}";
                                                        })
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
                                                ])
                                        ])->columnSpanFull(),
                                ])->columns(3),
                        ])->collapsible(),
                    ])
                    ->action(function (array $data) {
                        // dd($data);
                        $crearRequisicion = RequisicionController::updateDetalleRequisicion($data);
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
                    ->slideOver()
            ])
            ->bulkActions([
                BulkAction::make('aceptar')
                ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->label('Aceptar Requisición')
                    ->icon('heroicon-c-cog-8-tooth')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $enviarRequisicion = RequisicionController::enviarRequisicion($records);
                        if($enviarRequisicion){
                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-c-x-circle')
                                ->color('success')
                                ->iconColor('success')
                                ->body('La requisicion fue enviada con éxito')
                                ->send();
                        }
                    })
            ])
            ->checkIfRecordIsSelectableUsing(fn(DetalleRequisicion $record): bool => $record->status_id == 5)
            ->striped();
    }
}