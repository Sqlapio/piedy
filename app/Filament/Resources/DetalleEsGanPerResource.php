<?php

namespace App\Filament\Resources;

use App\Models\Rol;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Sucursal;
use Filament\Forms\Form;
use App\Models\PreNomina;
use Filament\Tables\Table;
use App\Models\DetalleEsGanPer;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DetalleEsGanPerResource\Pages;
use App\Filament\Resources\DetalleEsGanPerResource\RelationManagers;

class DetalleEsGanPerResource extends Resource
{
    protected static ?string $model = DetalleEsGanPer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Section::make('RANGO DE FECHAS Y SUCURSAL')
                    // ->description('Formulario de registro de productos para la venta y de consumo interno')
                    ->icon('heroicon-m-list-bullet')

                    //SECCION 1
                    ->schema([
                        //desde
                        DatePicker::make('fecha_ini')
                            ->label('Fecha Desde:')
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->format('Y-m-d')
                            ->required()
                            ->live(),
                        //hasta
                        DatePicker::make('fecha_fin')
                            ->label('Fecha Hasta:')
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->format('Y-m-d')
                            ->required()
                            ->live(),

                        Select::make('sucursal_id')
                            // ->relationship('sucursal', 'nombre')
                            ->options(Sucursal::all()->pluck('nombre', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(3),

                //SECCION 2
                Section::make('RANGO DE FECHAS Y SUCURSAL')
                    // ->description('Formulario de registro de productos para la venta y de consumo interno')
                    ->icon('heroicon-m-list-bullet')
                    ->schema([
                        Forms\Components\TextInput::make('mano_de_obra')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('otros_costos_directos')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('publicidad')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('alquiler')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('telefono')
                            ->tel()
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('internet')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('gastos_financieros')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('perdidas_no_recurrentes')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('ingresos_financieros')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('ganancias_no_recurrentes')
                            ->required()
                            ->numeric()
                            ->default(0.00),

                    ])->columns(5),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha_ini')
                    ->label('Fecha Desde:')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fecha Hasta:')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_usd')
                    ->label('Ingresos USD:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_bsd')
                    ->label('Ingresos BSD:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_totales_usd')
                    ->label('Ingresos Totales USD:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comisiones_empleados')
                    ->label('Comisiones Empleados:')
                    ->numeric()
                    ->sortable(),

                //TextInputColumns
                Tables\Columns\TextColumn::make('mano_de_obra')
                    ->label('Mano de Obra:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('otros_costos_directos')
                    ->label('Otros Costos Directos:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('publicidad')
                    ->label('Publicidad:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alquiler')
                    ->label('Alquiler:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Telefono:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internet')
                    ->label('Internet:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gastos_financieros')
                    ->label('Gastos Financieros:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('perdidas_no_recurrentes')
                    ->label('Perdidas No Recurrentes:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_financieros')
                    ->label('Ingresos Financieros:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ganancias_no_recurrentes')
                    ->label('Ganancias No Recurrentes:')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Usuario:')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estatus:')
                    ->badge()
                    ->colors([
                        'success' => 'abierto',
                        'danger' => 'cerrado',
                    ])
                    ->color('success')
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
            'index' => Pages\ListDetalleEsGanPers::route('/'),
            'create' => Pages\CreateDetalleEsGanPer::route('/create'),
            'edit' => Pages\EditDetalleEsGanPer::route('/{record}/edit'),
        ];
    }
}