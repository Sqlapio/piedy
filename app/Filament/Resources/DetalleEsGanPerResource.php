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
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_usd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_bsd')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mano_de_obra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('otros_costos_directos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('publicidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comisiones_empleados')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sueldos_empleados')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alquiler')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internet')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gastos_financieros')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('perdidas_no_recurrentes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_financieros')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ganancias_no_recurrentes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
