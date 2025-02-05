<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Comision;
use App\Models\Sucursal;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ComisionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ComisionResource\RelationManagers;

class ComisionResource extends Resource
{
    protected static ?string $model = Comision::class;

    protected static ?string $navigationIcon = 'heroicon-m-currency-dollar';

    protected static ?string $navigationGroup = 'Configuración';

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
            ->heading('COMISIONES')
            ->description('Lista de Comisiones generales del sistema')
            ->query(Comision::query()->orderBy('created_at', 'desc'))
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
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['hasta'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
                // SelectFilter::make('tienda')
                //     ->relationship('sucursal', 'nombre')
                //     ->attribute('sucursal_id')
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    // public static function getRelations(): array
    // {
    //     return [
    //         //
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComisions::route('/'),
            'create' => Pages\CreateComision::route('/create'),
            'edit' => Pages\EditComision::route('/{record}/edit'),
        ];
    }
}