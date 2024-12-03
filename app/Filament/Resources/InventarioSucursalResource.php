<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\InventarioSucursal;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\InventarioSucursalResource\Pages;
use App\Filament\Resources\InventarioSucursalResource\RelationManagers;


class InventarioSucursalResource extends Resource
{
    protected static ?string $model = InventarioSucursal::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-office';

    protected static ?string $navigationGroup = 'Manejo de Inventario';

    protected static ?string $navigationLabel = 'Inventario Sucursales';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('producto_id')
                    ->relationship('producto', 'id')
                    ->required(),
                Forms\Components\Select::make('sucursal_id')
                    ->relationship('sucursal', 'id')
                    ->required(),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('responsable')
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(InventarioSucursal::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->icon('heroicon-s-shopping-bag')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->icon('heroicon-c-building-office-2')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Existencia')
                    ->searchable()
                    ->icon('heroicon-o-square-3-stack-3d')
                    ->color('success')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('uso')
                    ->icon('heroicon-s-truck')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->searchable()
                    ->icon('heroicon-s-calendar-days')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->color('primary')
                    ->icon('heroicon-m-user')
                    ->searchable(),
            ])
            ->groups([
                'sucursal.nombre',
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
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                    ->exports([
                        // Pass a string
                        ExcelExport::make()->withFilename(date('d-m-Y') . '-inv-sucursales'),
                    ])
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
            'index' => Pages\ListInventarioSucursals::route('/'),
            'create' => Pages\CreateInventarioSucursal::route('/create'),
            'edit' => Pages\EditInventarioSucursal::route('/{record}/edit'),
        ];
    }
}