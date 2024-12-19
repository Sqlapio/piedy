<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use WireUi\Traits\Actions;
use Filament\Support\RawJs;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Controllers\CierreDiarioController;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\CierreDiario as ModelsCierreDiario;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
// use Filament\Forms\Components\TextInput;

class CierreDiario extends Component implements HasForms, HasTable
{
    use Actions;
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('CIERRE DIARIO')
            ->description('Tabla de cierre diario por turno')
            ->query(ModelsCierreDiario::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('sucursal_id', auth()->user()->sucursal_id))
            ->columns([
                TextColumn::make('total_ventas')
                    ->numeric(decimalPlaces: 0)
                    ->label('Venta Total($)')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('total_dolares_efectivo')
                    ->numeric(decimalPlaces: 0)
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->label('Efectivo($)')
                    ->searchable(),

                TextColumn::make('total_dolares_zelle')
                    ->numeric(decimalPlaces: 0)
                    ->icon('heroicon-m-credit-card')
                    ->color('success')
                    ->label('Zelle($)')
                    ->searchable(),

                TextColumn::make('total_bolivares')
                    ->label('Total Bolivares(Bs)')
                    ->money('VES')
                    ->searchable(),

                TextColumn::make('total_efectivo_bsd')
                    ->label('Efectivo(Bs)')
                    ->money('VES')
                    ->searchable(),

                TextColumn::make('total_pago_movil_bsd')
                    ->label('Pago Movil(Bs)')
                    ->money('VES')
                    ->searchable(),

                TextColumn::make('total_punto_venta_bsd')
                    ->label('Punto Venta(Bs)')
                    ->money('VES')
                    ->searchable(),

                TextColumn::make('total_transferencia_bsd')
                    ->label('Transferencia(Bs.)')
                    ->money('VES')
                    ->searchable(),

                TextColumn::make('ref_debito')
                    ->label('Ref. Débito')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ref_credito')
                    ->label('Ref. Credito')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ref_visaMaster')
                    ->label('Ref. Visa/Master')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Fecha de cierre')
                    ->icon('heroicon-s-calendar-days')
                    ->color('colorTree')
                    ->searchable(),

                TextColumn::make('responsable')
                    ->icon('heroicon-s-user')
                    ->color('colorOne')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('observaciones')
                    ->icon('heroicon-s-user')
                    ->color('colorOne')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                umeric'])
                    ->hidden(function () {
                        if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 3){
                            retTextInputColumn::make('efectivo_usd_real')
                    ->label('Efectivo($) recibido')
                    ->rules(['nurn false;
                        }
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        ModelsCierreDiario::where('id', $record->id)->first()
                        ->update([
                            'efectivo_usd_real' => $state,
                            'recibido_por' => Auth::user()->name,
                            'received_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                    }), 

                TextInputColumn::make('observ_recepcion')
                    ->label('Observaciones en la Entrega')
                    ->hidden(function () {
                        if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 3){
                            return false;
                        }
                    }),

                TextColumn::make('recibido_por')
                    ->icon('heroicon-s-user')
                    ->color('colorOne')
                    ->searchable()
                    ->hidden(function () {
                        if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 3){
                            return false;
                        }
                    }),

                TextColumn::make('received_at')
                    ->icon('heroicon-s-user')
                    ->color('colorOne')
                    ->label('Fecha de recepcion')
                    ->searchable()
                    ->hidden(function () {
                        if (Auth::user()->rol_id == 4 || Auth::user()->rol_id == 3) {
                            return false;
                        }
                    }),

            ])
            ->groups([
                'responsable',
            ])
            ->filters([
                DateRangeFilter::make('created_at')->timezone('America/Caracas'),
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ])
            ->headerActions([
                CreateAction::make()
                ->model(CierreDiario::class)
                ->form([
                    Section::make('Formulario')
                        ->description('Debe llenar los campos de forma correta')
                        ->icon('heroicon-s-newspaper')
                        ->schema([
                            Grid::make()
                            ->schema([

                                //Debito
                                TextInput::make('ref_debito')
                                    ->label('Ref. Debito')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->mask(RawJs::make(<<<'JS'
                                        $input.startsWith('34') || $input.startsWith('37') ? '999999' : '999999'
                                    JS))
                                    ->numeric(),
                                TextInput::make('monto_ref_debito')
                                    ->label('Monto Debito')
                                    ->prefixIcon('heroicon-s-building-library')
                                    ->mask(RawJs::make(<<<'JS'
                                        $money($input, ',')
                                    JS)),

                                //Credito
                                TextInput::make('ref_credito')
                                    ->label('Ref. Credito')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->mask(RawJs::make(<<<'JS'
                                        $input.startsWith('34') || $input.startsWith('37') ? '999999' : '999999'
                                    JS))
                                    ->numeric(),
                                TextInput::make('monto_ref_credito')
                                    ->label('Monto Credito')
                                    ->prefixIcon('heroicon-s-building-library')
                                    ->mask(RawJs::make(<<<'JS'
                                        $money($input, ',')
                                    JS)),

                                //Vida/Master
                                TextInput::make('ref_visaMaster')
                                    ->label('Ref. Visa/Master')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->mask(RawJs::make(<<<'JS'
                                        $input.startsWith('34') || $input.startsWith('37') ? '999999' : '999999'
                                    JS))
                                    ->numeric(),

                                TextInput::make('monto_ref_visaMaster')
                                    ->label('Monto Visa/Master')
                                    ->prefixIcon('heroicon-s-building-library')
                                    ->mask(RawJs::make(<<<'JS'
                                        $money($input, ',')
                                    JS)),
                                // ...
                            ]),
                        Textarea::make('observaciones')
                        ->autosize()

                        ])
                ])
                ->action(function (array $data) {
                    CierreDiarioController::cierreDiario(
                        $data['ref_debito'],
                        $data['monto_ref_debito'],
                        $data['ref_credito'],
                        $data['monto_ref_credito'],
                        $data['ref_visaMaster'],
                        $data['monto_ref_visaMaster'],
                        $data['observaciones']);

                })
            ]);
    }

    public function render()
    {
        return view('livewire.cierre-diario');
    }
}