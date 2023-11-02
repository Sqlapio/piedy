<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CitaResource\Pages;
use App\Filament\Resources\CitaResource\RelationManagers;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Servicio;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CitaResource extends Resource
{
    protected static ?string $model = Cita::class;

    protected static ?string $navigationIcon = 'heroicon-s-wallet';

    protected static ?string $navigationGroup = 'Tienda Sambil';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('cod_comision')->default('Pco-'.random_int(11111, 99999)),
                Select::make('cliente_id')
                    ->relationship('cliente', 'nombre')
                    // ->options(Comision::where('aplicacion', 'producto')->pluck('porcentaje', 'id'))
                    ->options(Cliente::all()->pluck('nombre', 'id'))
                    ->searchable(),
                Select::make('servicio_id')
                    ->relationship('servicio', 'descripcion')
                    // ->options(Comision::where('aplicacion', 'producto')->pluck('porcentaje', 'id'))
                    ->options(Servicio::all()->pluck('descripcion', 'id'))
                    ->searchable(),
                DatePicker::make('fecha')->format('d-m-Y'),
                TimePicker::make('hora')
                ->datalist([
                    '07:00',
                    '07:30',
                    '08:00',
                    '08:30',
                    '09:00',
                    '09:30',
                    '10:00',
                    '10:30',
                    '11:00',
                    '11:30',
                    '12:00',
                    '12:30',
                    '13:00',
                    '13:30',
                    '14:00',
                    '14:30',
                    '15:00',
                    '15:30',
                    '16:00',
                    '16:30',
                    '17:00',
                    '17:30',
                    '18:00',
                    '18:30',
                    '19:00',
                    '19:30',
                    '20:00',
                    '20:30',
                    '21:00',
                    '21:30',
                    '22:00',   
                ])
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_cita')->searchable(),
                TextColumn::make('cliente.nombre')->searchable(),
                TextColumn::make('servicio.descripcion')->searchable(),
                TextColumn::make('fecha')->searchable(),
                TextColumn::make('hora')->searchable(),
                IconColumn::make('status')
                ->options([
                    'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === 1,
                    'heroicon-m-hand-thumb-up' => fn ($state, $record): bool => $record->status === 2,
                    'heroicon-s-x-circle' => fn ($state, $record): bool => $record->status === 3,
                ])
                ->colors([
                    'info' => 1,
                    'success' => 2,
                    'danger' => 3,
                ])
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
            'index' => Pages\ListCitas::route('/'),
            'create' => Pages\CreateCita::route('/create'),
            'edit' => Pages\EditCita::route('/{record}/edit'),
        ];
    }    
}
