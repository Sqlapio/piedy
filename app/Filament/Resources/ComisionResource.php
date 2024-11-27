<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComisionResource\Pages;
use App\Filament\Resources\ComisionResource\RelationManagers;
use App\Models\Comision;
use App\Models\Sucursal;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComisionResource extends Resource
{
    protected static ?string $model = Comision::class;

    protected static ?string $navigationIcon = 'heroicon-m-currency-dollar';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Comisiones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FORMULARIO DE COMISIONES')
                ->description('Registro y definición de comisones por venta de productos y servícios')
                ->icon('heroicon-s-receipt-percent')
                ->schema([
                    TextInput::make('cod_comision')
                        ->label('Codigo de Comisión')
                        ->default('Pco-'.random_int(11111, 99999))
                        ->disabled(),
                    TextInput::make('porcentaje')
                        ->prefix('%')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100)
                        ->required(),
                    Select::make('aplicacion')
                        ->label('La Comisión se Aplica a:')
                        ->options([
                            'producto' => 'Producto',
                            'servicio' => 'Servicio',
                            'vip'      => 'VIP',
                            'membresia'  => 'Membresia',
                            'servicio-adicional'  => 'Servicio Adicional',
                        ]),
                    Select::make('beneficiario')
                        ->options([
                            'gerente' => 'Gerente',
                            'empleado' => 'Empleado',
                        ]),
                    Select::make('accion')
                        ->label('Afectación de venta')
                        ->options([
                            'directa' => 'Directa',
                            'indirecta' => 'Indirecta',
                        ]),
                    Select::make('sucursal_id')
                        ->options(Sucursal::all()->pluck('nombre', 'id'))
                    
                ])
                ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('cod_comision')->searchable()->label('Código'),
                TextColumn::make('porcentaje')
                ->searchable()
                ->label('Porcentaje(%)')
                ->color('success')
                ->icon('heroicon-m-receipt-percent'),
                
                TextColumn::make('aplicacion')
                ->icon('heroicon-o-document-check')
                ->searchable(),
                
                TextColumn::make('beneficiario')
                ->icon('heroicon-c-user-group')
                ->color('colorTree')
                ->searchable(),
                
                IconColumn::make('status')
                ->options([
                    'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === '1',
                    'heroicon-m-minus-circle' => fn ($state, $record): bool => $record->status === '2',
                ])
                ->colors([
                    'success' => '1',
                    'danger' => '2',
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListComisions::route('/'),
            'create' => Pages\CreateComision::route('/create'),
            'edit' => Pages\EditComision::route('/{record}/edit'),
        ];
    }
}