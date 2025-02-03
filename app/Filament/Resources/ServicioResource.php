<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\Rol;
use Filament\Forms;
use Filament\Tables;
use App\Models\Servicio;
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
use Filament\Tables\TablesServiceProvider;
use App\Filament\Resources\ServicioResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\ServicioResource\RelationManagers;

class ServicioResource extends Resource
{
    protected static ?string $model = Servicio::class;

    protected static ?string $navigationIcon = 'heroicon-m-puzzle-piece';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Lista de Servícios';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FORMULARIO DE SERVICIOS')
                ->description('Registro y definición de servicios para la plataforma')
                ->icon('heroicon-s-receipt-percent')
                ->schema([
                    TextInput::make('cod_servicio')->default('Sco-'.random_int(11111, 99999)),
                    TextInput::make('descripcion')->required(),
                    Select::make('categoria')
                        ->options([
                            'principal' => 'Principal',
                            'adicional' => 'Adicional',
                        ]),
                    Select::make('rol_id')
                        ->label('A qué rol pertenece?')
                        ->options(Rol::whereBetween('id', [1,2])->pluck('descripcion', 'id'))
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('descripcion')
                                ->required(),
                        ])
                        ->required(),
                    TextInput::make('costo')
                        ->prefix('$')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100)
                        ->required(),
                        Select::make('asignacion')
                        ->options([
                            'general' => 'General',
                            'vip' => 'VIP',
                            'membresia' => 'Membresia',
                        ])
                        ->required(),
                    Select::make('status')
                        ->options([
                            'activo' => 'Activo',
                            'inactivo' => 'Inactivo',
                        ])
                        ->required(),
                    Select::make('sucursal_id')
                        ->relationship('sucursal', 'nombre')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('descripcion')
                                ->required(),
                        ])
                        ->required(),
                ])
                ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('SERVICIOS')
            ->description('Listado de Servicios')
            ->query(Servicio::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('cod_servicio')->searchable()->label('Código'),
                TextColumn::make('sucursal.nombre')
                    ->icon('heroicon-s-home')
                    ->color('colorTree')
                    ->searchable()
                    ->label('Sucursal'),
                TextColumn::make('descripcion')
                    ->icon('heroicon-s-pencil')
                    ->color('colorOne')
                    ->searchable(),
                TextColumn::make('categoria')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'principal' => 'success',
                        'adicional' => 'warning',
                    })
                    ->searchable(),
                TextColumn::make('rol.descripcion')->label('Tipo de servício')->searchable(),
                TextColumn::make('costo')
                    ->color('success')
                    ->money('USD')
                    ->searchable()
                    ->label('Costo($)'),
                TextColumn::make('asignacion')
                    ->badge()
                    ->colors([
                        'info' => 'general',
                        'success' => 'vip',
                        'warning' => 'membresia',
                    ])
                    ->searchable(),
                    IconColumn::make('status')
                    ->options([
                        'heroicon-s-check-circle' => fn ($state, $record): bool => $record->status === 'activo',
                        'heroicon-m-minus-circle' => fn ($state, $record): bool => $record->status === 'inactivo',
                    ])
                    ->colors([
                        'danger' => 'inactivo',
                        'success' => 'activo',
                    ]),
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
                SelectFilter::make('tienda')
                    ->relationship('sucursal', 'nombre')
                    ->attribute('sucursal_id')
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
                    ExportBulkAction::make()
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
            'index' => Pages\ListServicios::route('/'),
            'create' => Pages\CreateServicio::route('/create'),
            'edit' => Pages\EditServicio::route('/{record}/edit'),
        ];
    }
}