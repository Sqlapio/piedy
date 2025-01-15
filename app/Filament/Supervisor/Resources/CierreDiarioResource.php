<?php

namespace App\Filament\Supervisor\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\CierreDiario;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use App\Http\Controllers\CierreDiarioController;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Supervisor\Resources\CierreDiarioResource\Pages;
use App\Filament\Supervisor\Resources\CierreDiarioResource\RelationManagers;

class CierreDiarioResource extends Resource
{
    protected static ?string $model = CierreDiario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                            ->autosize(),
                        Section::make('TOTALES DE VENTA')
                            ->description('Cantidades totales de venta por tipo de pago')
                            ->icon('heroicon-m-list-bullet')
                            ->schema([
                                TextInput::make('total_dolares_efectivo')
                                    ->label('Efectivo($)')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->numeric()
                                    ->default(function () {
                                        /** totales de pagos en Dolares*/
                                        $total_efectivo_usd = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Efectivo Usd')->sum('pago_usd');
                                        $total_efectivo_usd_productos = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoUsd', 'Efectivo Usd')->sum('montoUsd');

                                        return $total_efectivo_usd + $total_efectivo_usd_productos;
                                    })
                                    ->readOnly(),
                                TextInput::make('total_dolares_zelle')
                                    ->label('Zelle($)')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->numeric()
                                    ->default(function () {
                                        $total_zelle = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago', 'Zelle')->sum('pago_usd');
                                        $total_zelle_productos = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoUsd', 'Zelle')->sum('montoUsd');
                                        return $total_zelle + $total_zelle_productos;
                                    })
                                    ->readOnly(),
                                TextInput::make('total_pago_movil_bsd')
                                    ->label('Pago Movil(Bs.)')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->numeric()
                                    ->default(function () {
                                        $total_pago_movil_bsd = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago_dos', 'Pago movil')->sum('pago_bsd');
                                        $totalprod_pago_movil_bsd    = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoBsd', 'Pago movil')->sum('montoBsd');
                                        return $total_pago_movil_bsd + $totalprod_pago_movil_bsd;
                                    })
                                    ->readOnly(),
                                TextInput::make('total_transferencia_bsd')
                                    ->label('Tranferencia(Bs.)')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->numeric()
                                    ->default(function () {
                                        $total_transferencia_bsd = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago_dos', 'Transferencia')->sum('pago_bsd');
                                        $totalprod_transferencia_bsd = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoBsd', 'Transferencia')->sum('montoBsd');
                                        return $total_transferencia_bsd + $totalprod_transferencia_bsd;
                                    })
                                    ->readOnly(),
                                TextInput::make('total_punto_venta_bsd')
                                    ->label('Punto de Venta(Bs.)')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->numeric()
                                    ->default(function () {
                                        $total_punto_venta_bsd = VentaServicio::where('fecha_venta', date('d-m-Y'))->where('metodo_pago_dos', 'Punto de venta')->sum('pago_bsd');
                                        $totalprod_punto_venta_bsd   = VentaProducto::where('fecha_venta', date('d-m-Y'))->where('metodoBsd', 'Punto de venta')->sum('montoBsd');
                                        return $total_punto_venta_bsd + $totalprod_punto_venta_bsd;
                                    })
                                    ->readOnly(),

                            ])
                            ->columns(5),
                        Section::make('MANEJO DE EFECTIVO')
                            ->description('Informacion para la entrega del efectivo en tienda')
                            ->icon('heroicon-m-list-bullet')
                            ->schema([
                                TextInput::make('efectivo_usd_real')
                                    ->label('Efectivo($) recibido')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->mask(RawJs::make(<<<'JS'
                                                    $input.startsWith('34') || $input.startsWith('37') ? '999999' : '999999'
                                                JS))
                                    ->numeric(),
                                TextInput::make('recibido_por')
                                    ->label('Efectivo recibido por:')
                                    ->default(Auth::user()->name)
                                    ->readOnly(),
                                TextInput::make('received_at')
                                    ->label('Fecha de Entrega de Efectivo')
                                    ->default(date('Y-m-d H:i:s'))
                                    ->readOnly(),
                            ])
                            ->columns(3),
                        Textarea::make('observ_recepcion')
                            ->label('Observaciones en la Entrega de Efectivo')


                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                /**
                 * DOLARES
                 * ----------------------------------------
                 */
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
                //---------------------------------------------

                /**
                 * BOLIVARES
                 * ----------------------------------------
                 */
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
                //---------------------------------------------------

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

                TextColumn::make('efectivo_usd_real')
                    ->label('Efectivo($) recibido'),

                TextColumn::make('observ_recepcion')
                    ->label('Observaciones en la Entrega'),

                TextColumn::make('recibido_por')
                    ->icon('heroicon-s-user')
                    ->color('colorOne')
                    ->searchable()
                    ->hidden(function () {
                        if (Auth::user()->rol_id == 4 || Auth::user()->rol_id == 3) {
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCierreDiarios::route('/'),
            'create' => Pages\CreateCierreDiario::route('/create'),
            'edit' => Pages\EditCierreDiario::route('/{record}/edit'),
        ];
    }
}
