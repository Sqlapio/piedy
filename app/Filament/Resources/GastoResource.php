<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GastoResource\Pages;
use App\Filament\Resources\GastoResource\RelationManagers;
use App\Models\Gasto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;

class GastoResource extends Resource
{
    protected static ?string $model = Gasto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero_factura')
                    ->label('Nro. Factura Piedy')
                    ->maxLength(255)
                    ->default(function () {
                        $ultimo_correlativo = Gasto::where('numero_factura', 'like', '%Pcf-%')->latest()->first();
                        if(isset($ultimo_correlativo))
                        {
                            $parte_entera = intval(str_replace('Pcf-', '', $ultimo_correlativo->numero_factura));
                            $sum_correlativo = $parte_entera + 1;

                        }else{
                            $sum_correlativo = 1;
                        }

                        $numero_factura = 'Pcf-'.str_pad($sum_correlativo, 6, '0', STR_PAD_LEFT);
                        return $numero_factura;
                    }),
                Forms\Components\TextInput::make('numero_factura_gasto')
                    ->label('Nro. de Factura del gasto')
                    ->rules(['required','numeric'])
                    ->validationMessages([
                        'required'  => 'Campo requerido',
                        'numeric'    => 'Solo admite números',
                    ]),
                Forms\Components\DatePicker::make('fecha_factura')
                    ->label('Fecha de Factura del gasto')
                    ->format('d-m-Y'),
                Forms\Components\TextInput::make('descripcion')
                    ->label('Descripción del gasto')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('forma_pago')
                ->label('Forma de Pago')
                ->required()
                ->live()
                    ->options([
                        'dolares' => 'Dolares',
                        'bolivares' => 'Bolivares',
                    ]),
                Forms\Components\TextInput::make('monto_usd')
                    ->label('Monto en USD($)')
                    ->numeric()
                    ->hidden(function (Get $get) {
                        if($get('forma_pago') == 'dolares')
                        {
                            return false;
                        }else{
                            return true;
                        }
                    })
                    ->default(0.00),
                Forms\Components\TextInput::make('monto_bsd')
                    ->label('Monto en BSD(Bs.)')
                    ->hidden(function (Get $get) {
                        if($get('forma_pago') == 'bolivares')
                        {
                            return false;
                        }else{
                            return true;
                        }
                    })
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('fecha')
                    ->default(now()->format('d-m-Y'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('responsable')
                    ->required()
                    ->default(auth()->user()->name),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Gasto::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('numero_factura')
                ->label('Nro. Factura Piedy')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('numero_factura_gasto')
                ->label('Nro. Factura Gasto')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('descripcion')
                ->label('Descripcion del Gasto')
                ->icon('heroicon-m-document-check')
                ->searchable(),

                Tables\Columns\TextColumn::make('monto_usd')
                ->icon('heroicon-s-currency-dollar')
                ->money('USD')
                ->sortable(),

                Tables\Columns\TextColumn::make('monto_bsd')
                ->icon('heroicon-m-credit-card')
                ->money('VES')
                ->sortable(),

                Tables\Columns\TextColumn::make('forma_pago')
                ->label('Forma de Pago')
                ->searchable(),

                Tables\Columns\TextColumn::make('fecha')
                ->label('Regiastrado el:')
                ->icon('heroicon-m-calendar-days')
                ->searchable(),

                Tables\Columns\TextColumn::make('fecha_factura')
                ->label('Fecha Factura de Gasto')
                ->icon('heroicon-m-calendar-days')
                ->searchable(),

                Tables\Columns\TextColumn::make('responsable')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('created_at')
                ->form([
                    DatePicker::make('desde'),
                    DatePicker::make('hasta'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['desde'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['hasta'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['desde'] ?? null) {
                        $indicators['desde'] = 'Venta desde ' . Carbon::parse($data['desde'])->toFormattedDateString();
                    }
                    if ($data['hasta'] ?? null) {
                        $indicators['hasta'] = 'Venta hasta ' . Carbon::parse($data['hasta'])->toFormattedDateString();
                    }

                    return $indicators;
                }),
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
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
            'index' => Pages\ListGastos::route('/'),
            'create' => Pages\CreateGasto::route('/create'),
            'edit' => Pages\EditGasto::route('/{record}/edit'),
        ];
    }
}
